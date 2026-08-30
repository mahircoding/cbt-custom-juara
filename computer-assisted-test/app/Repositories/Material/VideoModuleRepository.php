<?php

namespace App\Repositories\Material;

use App\Models\Material\VideoModule;
use App\Repositories\Contracts\Material\VideoModuleInterface;
use App\Repositories\BaseRepository;
use App\Services\UploadService;
use Auth;

class VideoModuleRepository extends BaseRepository implements VideoModuleInterface
{
    /**
     * @var
     */
    protected $model;

    protected $image_path = 'upload_files/video_modules';

    public function __construct()
    {
        $this->model = new VideoModule();
    }

    public function find($id)
    {
        return $this->model->with(['category', 'userAccess', 'memberCategories', 'detailVideoModule' => function ($query) {
            $query->orderBy('order', 'ASC');
        }])->find($id);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $videoModules = $this->model->query();

        if(isset($params->search) && !empty($params->search)) {
            $videoModules->where('title', 'like', '%' . $params->search . '%');
        }

        if(Auth::user()->level == 3) {
            $videoModules->where('user_id', Auth::user()->id);
        }

        $videoModules = $videoModules->with('category', 'memberCategories', 'user')
                        ->withCount('detailVideoModule')
                        ->join('categories', 'video_modules.category_id', '=', 'categories.id')
                        ->orderBy('categories.order', 'ASC')
                        ->orderBy('video_modules.order', 'ASC')
                        ->paginate($limit);

        $videoModules->appends([
            'search' => $params->search,
        ]);

        return $videoModules;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;
        $input['period_type'] = $attributes->period_type == null ? null : $attributes->period_type;
        $input['active_period'] = $attributes->period_type == null ? null : $attributes->active_period;
        
        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            $input['thumbnail'] = $filename;
        }

        $videoModule = $this->model->create($input);

        if($attributes->member_category_ids) {
            $memberCategoryIds = [];

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $videoModule->memberCategories()->sync($memberCategoryIds);
        }

        return $videoModule;
    }

    public function update($attributes, $id)
    {
        $input = $attributes->except('_token','_method');
        $videoModule = $this->find($id);

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;
        $input['period_type'] = $attributes->period_type == null ? null : $attributes->period_type;
        $input['active_period'] = $attributes->period_type == null ? null : $attributes->active_period;

        if($attributes->hasFile('thumbnail')){
            $file = $attributes->file('thumbnail')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('thumbnail'), $this->image_path);
            (new UploadService())->deleteFile($videoModule->thumbnail, $this->image_path);
            $input['thumbnail'] = $filename;
        } else {
            $input['thumbnail'] = $videoModule->thumbnail;
        }

        $memberCategories = [];

        $videoModule->update($input);

        if($attributes->member_category_ids) {
            $memberCategoryIds = [];

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $videoModule->memberCategories()->sync($memberCategoryIds);
        }

        return $videoModule;
    }

    public function delete($id)
    {
        $videoModule = $this->model->find($id);
        (new UploadService())->deleteFile($videoModule->thumbnail, $this->image_path);
        return $videoModule->delete();
    }
}
