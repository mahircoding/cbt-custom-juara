<?php

namespace App\Repositories\Material;

use App\Models\Material\Classroom;
use App\Repositories\Contracts\Material\ClassroomInterface;
use App\Repositories\BaseRepository;
use App\Models\Setting\Setting;
use Auth;

class ClassroomRepository extends BaseRepository implements ClassroomInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Classroom();
        $this->setting = Setting::first();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $classrooms = $this->model->query();

        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';

            $classrooms->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                      ->orWhere('title', 'like', $searchTerm);
            });
        }

        if(Auth::user()->level == 3) {
            $classrooms->where('user_id', Auth::user()->id);
        }

        if(isset($params->category_id) && !empty($params->category_id)) $classrooms->where('category_id', $params->category_id);

        if(Auth::user()->level == 2) {
            $classrooms->whereIn('status', $this->setting->classroom_statuses);
        }
        
        $classrooms = $classrooms->with(['category', 'userAccess', 'memberCategories', 'user'])
                        ->orderBy('created_at', isset($params->order) && !empty($params->order) ? $params->order : 'DESC')
                        ->paginate($limit);

        $classrooms->appends([
            'search' => $params->search,
            'category_id' => $params->category_id,
            'order' => $params->order
        ]);

        return $classrooms;
    }

    public function create($attributes)
    {
        $input = $attributes->all();

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;

        $classroom = $this->model->create($input);

        if($attributes->member_category_ids) {
            $memberCategoryIds = [];

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $classroom->memberCategories()->sync($memberCategoryIds);
        }

        return $classroom;
    }

    public function update($attributes, $id)
    {
        $input = $attributes->except('_token','_method');
        $classroom = $this->find($id);

        $input['price_before_discount'] = $attributes->price_type == 1 ? null : $attributes->price_before_discount;
        $input['price_after_discount'] = $attributes->price_type == 1 ? null : $attributes->price_after_discount;

        $memberCategories = [];

        $classroom->update($input);

        if($attributes->member_category_ids) {
            $memberCategoryIds = [];

            foreach ($attributes->member_category_ids as $memberCategory) {
                $memberCategoryIds[] = $memberCategory['id'];
            }
            
            $classroom->memberCategories()->sync($memberCategoryIds);
        }

        return $classroom;
    }

    public function find($id)
    {
        return $this->model->with(['user', 'category', 'memberCategories'])->find($id);
    }
}
