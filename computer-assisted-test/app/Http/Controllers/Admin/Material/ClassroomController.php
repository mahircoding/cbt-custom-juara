<?php

namespace App\Http\Controllers\Admin\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material\Classroom;
use App\Repositories\Material\ClassroomRepository;
use App\Http\Requests\Material\ClassroomRequest;
use App\Repositories\MasterData\CategoryRepository;
use App\Repositories\User\MemberCategoryRepository;
use App\Repositories\User\UserRepository;
use Session;

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
        Session::put('queryStringClassrooms', $request->getQueryString());

        return inertia('Admin/Material/Classroom/Index', [
            'categories' => (new CategoryRepository())->all(),
            'classrooms' => $this->classroomRepository->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Material/Classroom/Create', [
            'categories' => (new CategoryRepository())->all(),
            'user_id' => auth()->user()->level == 3 ? auth()->user()->id : null,
            'users' => (new UserRepository())->getAllTeacher(),
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomRequest $request)
    {
        try {
            $this->classroomRepository->create($request);

            return redirect()->route('admin.classrooms.index');

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $classroom = $this->classroomRepository->find($id);

        if (!$classroom) {
            return abort(404, 'uppss....');
        }
        
        return inertia('Admin/Material/Classroom/Show', [
            'classroom' => $classroom,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classroom = Classroom::with([
            'memberCategories' => function ($query) {
                $query->select('member_categories.id', 'member_categories.name');
            }
        ])->find($id);

        $memberCategorySelected = $classroom->memberCategories;

        if(!$classroom) return abort('404', 'uppss....');

        return inertia('Admin/Material/Classroom/Edit', [
            'classroom' => $classroom,
            'categories' => (new CategoryRepository())->all(),
            'memberCategorySelected' => $memberCategorySelected,
            'categories' => (new CategoryRepository())->all(),
            'users' => (new UserRepository())->getAllTeacher(),
            'memberCategories' => (new MemberCategoryRepository())->getAllActivated()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomRequest $request, $id)
    {
        try {
            if(!$this->classroomRepository->find($id)) return abort('404', 'uppss....');

            $this->classroomRepository->update($request, $id);

            $queryString = Session::get('queryStringClassrooms');
            return redirect(route('admin.classrooms.index') . (!empty($queryString) ? '?'.$queryString : ''));

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->classroomRepository->delete($id);
            
            return redirect()->back();

        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $classroom = $this->classroomRepository->find($id);

            if(!$classroom) return abort('404', 'uppss....');

            $this->classroomRepository->find($id)->update(['status' => $request->status]);

            return redirect()->back();
            
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }
}
