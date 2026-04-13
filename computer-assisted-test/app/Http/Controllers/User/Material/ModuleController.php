<?php

namespace App\Http\Controllers\User\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Material\ModuleRepository;
use App\Repositories\Material\DetailModuleRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Models\MemberCategoryUser;
use App\Models\Setting\Setting;
use Auth;
use Carbon\Carbon;

class ModuleController extends Controller
{
    protected $moduleRepository, $detailModuleRepository;

    public function __construct(ModuleRepository $moduleRepository, DetailModuleRepository $detailModuleRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->detailModuleRepository = $detailModuleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('User/Material/Module/Index', [
            'categoryModules' => (new CategoryRepository())->getCategoryModules(),
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
        $module = $this->moduleRepository->find($id);
        $category = (new CategoryRepository())->find($module->category_id);

        if(!$module || !$category) {
            return abort('404');
        }
        
        return inertia('User/Material/Module/Show', [
            'module' => $module,
            'category' => $category,
            'userMemberCategories' => MemberCategoryUser::where('user_id', Auth::user()->id)->where('expired_date', '>', Carbon::now())->get()
        ]);
    }

    public function detailModule($moduleId, $detailModuleId)
    {
        $setting = Setting::first();

        $module = $this->moduleRepository->find($moduleId);
        $detailModule = $this->detailModuleRepository->find($detailModuleId);

        if(!$module || !$detailModule) {
            return abort('404');
        }

        $salesType = $setting->transaction_sale_type == 1 ? $setting->{'module_material_sales_type'} : $module->category->{'module_material_sales_type'};
        $enable = $setting->transaction_sale_type == 1 ? $setting->{'enable_module_material_sales'} : $module->category->{'enable_module_material_sales'};

        if(Auth::user()->member_type == 2 && $enable == 1) {

            $checkAcess = false;

            $checkMemberCategories = $this->checkMemberCategories(
                MemberCategoryUser::where([
                    'category_id' => $module->category_id, 
                    'user_id' => Auth::user()->id
                ])
                ->where('expired_date', '>', Carbon::now())
                ->pluck('member_category_id'), 
                $module->memberCategories->pluck('id')->toArray() // Mengonversi koleksi menjadi array
            );

            if($salesType == 1 && count($module->userAccess) > 0) {
                $checkAcess = true;
            }

            if($salesType == 2 && $checkMemberCategories == true) {
                $checkAcess = true;
            }

            if($salesType == 3 && (count($module->userAccess) > 0 || $checkMemberCategories == true)) {
                $checkAcess = true;
            }

            if($checkAcess === false) {
                session()->flash('error', 'Anda tidak memiliki akses ke modul atau materi ini. Silakan melakukan transaksi terlebih dahulu.');
                return redirect()->back();
            }
        }

        return redirect()->away($detailModule->link);
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
