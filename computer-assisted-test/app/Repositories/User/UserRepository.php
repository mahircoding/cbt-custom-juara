<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Contracts\User\UserInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MasterData\Student;
use App\Models\Setting\Setting;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;
use Auth;
use App\Services\UploadService;

class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * @var
     */
    protected $model, $setting;

    protected $image_path = 'upload_files/photos';

    public function __construct()
    {
        $this->model = new User();
        $this->setting = Setting::first();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';

            $users->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('name', 'like', $searchTerm)
                      ->orWhere('username', 'like', $searchTerm);
            });
        }

        if (!empty($params->class_name)) {
            $classNameTerm = '%' . $params->class_name . '%';
            $users->where('class_name', 'like', $classNameTerm);
        }
        
        if(isset($params->level)) $users->where('level', $params->level);
        if(isset($params->member_type)) $users->where('member_type', $params->member_type);
        if(isset($params->status_active)) $users->where('is_active', (int) $params->status_active);

        $users = $users->orderBy('created_at', 'ASC')->paginate($limit);

        $users->appends([
            'search' => $params->search,
            'level' => $params->level,
            'status_active' => $params->status_active,
            'member_type' => $params->member_type,
        ]);

        return $users;
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 20)
    {
        $users = $this->model->query();

        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('name', 'like', $searchTerm)
                      ->orWhere('username', 'like', $searchTerm);
            });
        }

        $users = $users->where('level', 2)->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $users;
    }

    public function getAllWithParams($params)
    {
        $users = $this->model->query();

        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('name', 'like', $searchTerm)
                      ->orWhere('username', 'like', $searchTerm);
            });
        }

        $users = $users->where('level', 2)->orderBy('created_at', 'ASC')->get();
        return $users;
    }

    public function getAllUserStudentSimplePaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $users->where('name', 'like', '%' . $params->search . '%');
        $users = $users->with(['student'])->where('level', 2)->orderBy('created_at', 'ASC')->paginate($limit);
        return $users;
    }

    public function create($attributes)
    {
        $input = $attributes->all();
        $input['password'] = bcrypt($input['password']);
    
        if($attributes->hasFile('photo')){
            $file = $attributes->file('photo')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('photo'), $this->image_path);
            $input['photo'] = $filename;
        }

        $user = $this->model->create($input);

        if($this->setting->category_access == 2 && $input['level'] == 2) {
            $categoryIds = [];

            foreach ($input['category_ids'] as $category) {
                $categoryIds[] = $category['id'];
            }

            $user->categories()->sync($categoryIds);
        }

        $input['member_type'] = $input['level'] == 2 ? $input['member_type'] : null;
        $input['user_id'] = $user->id;
        $input['is_member'] = $input['member_type'] == 1 ? 1 : 0;

        Student::create($input);

        return $user;
    }

    public function update($attributes, $id)
    {
        $user = $this->model->find($id);

        $input = $attributes->all();

        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            $input = Arr::except($input,array('password'));
        }

        $input['commission_type'] = !isset($input['commission_type']) || $input['commission_type'] == null ? null : $input['commission_type'];
        $input['commission'] = !isset($input['commission_type']) || $input['commission_type'] == null ? null : $input['commission'];

        $categoryIds = [];

        if($this->setting->category_access == 2 && $input['level'] == 2) {
            foreach ($input['category_ids'] as $category) {
                $categoryIds[] = $category['id'];
            }
        }

        $user->categories()->sync($categoryIds);
        
        if($attributes->hasFile('photo')){
            $file = $attributes->file('photo')->getClientOriginalName();
            $filename = (new UploadService())->uploadFile($attributes->file('photo'), $this->image_path);
            (new UploadService())->deleteFile($user->photo, $this->image_path);
            $input['photo'] = $filename;
        } else {
            $input['photo'] = $user->photo;
        }

        $input['member_type'] = $input['level'] == 2 ? $input['member_type'] : null;

        $user->update($input);

        Student::updateOrInsert(
            ['user_id' => $user->id],
            [
                'id' => Uuid::uuid4()->toString(),
                'province_id' => $input['province_id'],
                'city_id' => $input['city_id'],
                'district_id' => $input['district_id'],
                'village_id' => $input['village_id'],
                'address' => $input['address'],
                'phone_number' => $input['phone_number'],
                'gender' => $input['gender'],
                'is_member' => $input['member_type'] == 1 ? 1 : 0
            ]
        );

        return $user;
    }

    public function delete($id)
    {
        $user = $this->model->find($id);
        (new UploadService())->deleteFile($user->photo, $this->image_path);
        return $user->delete();
    }

    public function find($id)
    {
        return $this->model->with([
            'categories',
            'referrer',
            'memberCategoryUser.category',
            'memberCategoryUser.memberCategory',
            'student.province',
            'student.city',
            'student.district',
            'student.village',
            'userAccess.access',
            'userAccess.access.category',
        ])->find($id);
    }
    
    public function getTotalStudent()
    {
        return $this->model->where('level', 2)->count();
    }

    public function getTotalStudentActive()
    {
        return $this->model->where(['level' => 2, 'is_active' => 1])->count();
    }

    public function getTotalStudentNonActive()
    {
        return $this->model->where(['level' => 2, 'is_active' => 0])->count();
    }

    public function getTotalStudentMember()
    {
        return $this->model->where('level', 2)->whereHas('student', function(Builder $query) {
            $query->where('is_member', 1);
        })->count();
    }

    public function getUserCommision($userId)
    {
        return $this->model->with('userCommission')->withCount('commission')->find($userId);
    }

    public function referrals()
    {
        return $this->model->where('referrer_id', Auth::user()->id)->withCount('transactionReferrals')->paginate(10);
    }

    public function getAllTeachers()
    {
        return $this->model->where(['level' => 3, 'is_active' => 1])->get();
    }

    public function getAllUserNotStudent()
    {
        return $this->model->where('level', '<>', 2)->get();
    }

    public function getAllTeacher()
    {
        return $this->model->where('level', 3)->get();
    }
}
