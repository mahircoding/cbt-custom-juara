<?php

namespace App\Http\Controllers\User\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Material\ClassroomRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Models\MemberCategoryUser;
use App\Models\Setting\Setting;
use Auth;
use Carbon\Carbon;

class ClassroomController extends Controller
{
    protected $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/Material/Classroom/Index', [
            'classrooms' => $this->classroomRepository->getAllPaginatedWithParams($request),
            'categories' => (new CategoryRepository())->getAllProduction(),
            'userMemberCategories' => MemberCategoryUser::where('user_id', Auth::user()->id)->where('expired_date', '>', Carbon::now())->get()
        ]);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $classroom = $this->classroomRepository->find($id);

        if(!$classroom) {
            return abort('404');
        }

        $setting = Setting::first();

        $classroom = $this->classroomRepository->find($id);

        $salesType = $setting->transaction_sale_type == 1 ? $setting->{'classroom_sales_type'} : $classroom->category->{'classroom_sales_type'};
        $enable = $setting->transaction_sale_type == 1 ? $setting->{'enable_classroom_sales'} : $classroom->category->{'enable_classroom_sales'};

        if(Auth::user()->member_type == 2 && $enable == 1) {

            $checkAcess = false;

            $checkMemberCategories = $this->checkMemberCategories(
                MemberCategoryUser::where([
                    'category_id' => $classroom->category_id, 
                    'user_id' => Auth::user()->id
                ])
                ->where('expired_date', '>', Carbon::now())
                ->pluck('member_category_id'), 
                $classroom->memberCategories->pluck('id')->toArray() // Mengonversi koleksi menjadi array
            );

            if($salesType == 1 && count($classroom->userAccess) > 0) {
                $checkAcess = true;
            }

            if($salesType == 2 && $checkMemberCategories == true) {
                $checkAcess = true;
            }

            if($salesType == 3 && (count($classroom->userAccess) > 0 || $checkMemberCategories == true)) {
                $checkAcess = true;
            }

            if($checkAcess === false) {
                session()->flash('error', 'Anda tidak memiliki akses ke ruang kelas ini. Silakan melakukan transaksi terlebih dahulu.');
                return redirect()->route('user.classrooms.index');
            }
        }

        return inertia('User/Material/Classroom/Show', [
            'classroom' => $classroom,
        ]);
    }

    function checkMemberCategories($data, $categories) 
    {
        if ($categories && count($categories) > 0) {
            $memberCategoryIds = $data->toArray(); 
            $memberCategoryIds = $memberCategoryIds; 

            return count(array_intersect($memberCategoryIds, $categories)) > 0;
        } else {
            return true;
        }
    }
}
