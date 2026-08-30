<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Auth;
use App\Models\MenuUser;
use App\Models\Setting\Setting;
use App\Models\MasterData\Category;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = Auth::user();
        $setting = Setting::first();

        return array_merge(parent::share($request), [
            'session' => collect(['status', 'success', 'error', 'failed', 'warning'])->mapWithKeys(fn($key) => [$key => fn() => $request->session()->get($key)]),
            'auth' => ['user' => $user],
            'app' => ['url' => config('app.url')],
            'voucherCategories' => optional($setting)->category_access == 1
                ? Category::where('development_status', 'production')->orderBy('order')->get()
                : ($user ? $user->categories()->where('development_status', 'production')->orderBy('order', 'ASC')->get() : []),
            'setting' => $setting,
            'menu_users' => MenuUser::all()
        ]);
    }
}
