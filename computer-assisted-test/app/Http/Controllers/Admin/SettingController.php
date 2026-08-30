<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\Setting\Setting\{ApplicationRequest, AuthenticationRequest, NotificationRequest, TransactionRequest, SiteConfigurationRequest, AffiliateRequest};
use App\Traits\Uploadable;
use App\Repositories\Transaction\PaymentMethodRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Models\MenuUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestSendMail;
use App\Models\MasterData\Category;
use App\Models\User;
use DB;

class SettingController extends Controller
{
    use Uploadable;

    protected $image_path = 'upload_files/settings';

    // Helper untuk menghapus dan meng-upload file
    private function handleFileUpload($request, $field, $setting, &$input)
    {
        if ($request->hasFile($field)) {
            $this->deleteFile($setting->{$field}, $this->image_path);
            $file = $request->file($field)->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $input[$field] = $this->uploadFile($request->file($field), $filename, $this->image_path);
        } else {
            $input[$field] = $setting->{$field};
        }
    }

    // Generalized store method
    private function storeSetting($request, $fields, $redirectRoute)
    {
        try {
            $setting = Setting::first();
            $input = $request->all();

            if ($setting) {
                foreach ($fields as $field) {
                    $this->handleFileUpload($request, $field, $setting, $input);
                }

                if($request->category_access == 2) {
                    $categoryIds = Category::where('development_status', 'production')->orderBy('order', 'ASC')->pluck('id')->toArray();

                    User::doesntHave('categories')
                        ->where('level', 2)
                        ->chunk(100, function ($users) use ($categoryIds) {
                            $dataToInsert = [];

                            foreach ($users as $user) {
                                foreach ($categoryIds as $categoryId) {
                                    $dataToInsert[] = [
                                        'user_id' => $user->id,
                                        'category_id' => $categoryId
                                    ];
                                }
                            }
                            
                            if (!empty($dataToInsert)) {
                                DB::table('category_user')->insert($dataToInsert);
                            }
                        });
                }
                $setting->update($input);

                if (isset($input['categories']) && is_array($input['categories'])) {
                    foreach ($input['categories'] as $categoryId => $categoryData) {
                        Category::where('id', $categoryId)->update([
                            'enable_practice_question_sales' => $categoryData['enable_practice_question_sales'] ?? 0,
                            'practice_question_sales_type' => $categoryData['practice_question_sales_type'] ?? null,
                            'enable_tryout_sales' => $categoryData['enable_tryout_sales'] ?? 0,
                            'tryout_sales_type' => $categoryData['tryout_sales_type'] ?? null,
                            'enable_module_material_sales' => $categoryData['enable_module_material_sales'] ?? 0,
                            'module_material_sales_type' => $categoryData['module_material_sales_type'] ?? null,
                            'enable_video_module_sales' => $categoryData['enable_video_module_sales'] ?? 0,
                            'video_module_sales_type' => $categoryData['video_module_sales_type'] ?? null,
                            'enable_course_sales' => $categoryData['enable_course_sales'] ?? 0,
                            'course_sales_type' => $categoryData['course_sales_type'] ?? null,
                            'enable_classroom_sales' => $categoryData['enable_classroom_sales'] ?? 0,
                            'classroom_sales_type' => $categoryData['classroom_sales_type'] ?? null,
                        ]);
                    }
                }

            } else {
                foreach ($fields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field)->getClientOriginalName();
                        $filename = pathinfo($file, PATHINFO_FILENAME);
                        $input[$field] = $this->uploadFile($request->file($field), $filename, $this->image_path);
                    }
                }
                Setting::create($input);
            }

            session()->flash('success', 'Data Berhasil Diupdate');
            return redirect()->route($redirectRoute);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());
            return redirect()->back()->withInput($request->all());
        }
    }

    public function deleteBackground()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->deleteFile($setting->authentication_background, $this->image_path);
            $setting->update(['authentication_background' => null]);
        }
        return redirect()->route('admin.setting.application');
    }

    public function testSendMessage(Request $request)
    {
        try {
            $request->notification_type == 1 
                ? sendWhatsappNotification($request->contact, $request->message) 
                : Mail::to($request->contact)->send(new TestSendMail($request->message));

            return redirect()->back()->with('success', 'Pesan dikirim. Silakan cek pesan pada '.($request->notification_type == 1 ? 'nomor WhatsApp' : 'Email').' tujuan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim pesan. '. $e->getMessage());
        }
    }

    public function application()
    {
        return inertia('Admin/Setting/Setting/Application')->with([
            'setting' => Setting::first() ?? [],
            'dataStatus' => ['active', 'inactive', 'inprogress']
        ]);
    }

    public function storeApplication(ApplicationRequest $request)
    {
        return $this->storeSetting($request, ['logo', 'authentication_background', 'favicon', 'signed_image'], 'admin.setting.application');
    }

    public function authentication()
    {
        $loginTypes = [
            ["type" => "code"],
            ["type" => "email"],
            ["type" => "username"],
          ];

        return inertia('Admin/Setting/Setting/Authentication')->with([
            'setting' => Setting::first() ?? [],
            'loginTypes' => $loginTypes,
            'menuUsers' => MenuUser::where(['is_active' => 1])->orderBy('order', 'ASC')->get()
        ]);
    }

    public function storeAuthentication(AuthenticationRequest $request)
    {
        return $this->storeSetting($request, [], 'admin.setting.authentication');
    }

    public function transaction()
    {
        return inertia('Admin/Setting/Setting/Transaction')->with([
            'setting' => Setting::first() ?? [],
            'categories' => (new CategoryRepository())->all(),
            'paymentMethods' => (new PaymentMethodRepository())->getAllActivated(),
        ]);
    }

    public function storeTransaction(TransactionRequest $request)
    {
        return $this->storeSetting($request, [], 'admin.setting.transaction');
    }

    public function notification()
    {
        return inertia('Admin/Setting/Setting/Notification')->with([
            'setting' => Setting::first() ?? [],
        ]);
    }

    public function storeNotification(NotificationRequest $request)
    {
        return $this->storeSetting($request, [], 'admin.setting.notification');
    }

    public function siteConfiguration()
    {
        return inertia('Admin/Setting/Setting/SiteConfiguration')->with([
            'setting' => Setting::first() ?? [],
        ]);
    }

    public function storeSiteConfiguration(SiteConfigurationRequest $request)
    {
        return $this->storeSetting($request, ['meta_image', 'meta_favicon'], 'admin.setting.site-configuration');
    }

    public function affiliate()
    {
        return inertia('Admin/Setting/Setting/Affiliate')->with([
            'setting' => Setting::first() ?? [],
        ]);
    }
    
    public function storeAffiliate(AffiliateRequest $request)
    {
        return $this->storeSetting($request, [], 'admin.setting.affiliates');
    }
}