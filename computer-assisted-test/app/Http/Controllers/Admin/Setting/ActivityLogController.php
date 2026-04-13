<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query();
        if(isset($request->log_name) && !empty($request->log_name)) $activities->where('log_name', $request->log_name);
        if(isset($request->event) && !empty($request->event)) $activities->where('event', $request->event);
        if(isset($request->start_date) && !empty($request->start_date) && isset($request->end_date) && !empty($request->end_date)) $activities->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date]);
        $activities = $activities->with(['causer'])->latest()->paginate(50);

        $activities->appends([
            'log_name' => $request->log_name,
            'event' => $request->event,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return inertia('Admin/Setting/ActivityLog/Index', [
            'activities' => $activities
        ]);
    }
}
