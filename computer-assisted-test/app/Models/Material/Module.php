<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\Category;
use App\Models\MemberCategory;
use App\Models\Material\DetailModule;
use App\Models\UserAccess;
use App\Models\User;
use Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class Module extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'title',
        'thumbnail',
        'description',
        'order',
        'price_type', // 1 free, 2 paid
        'price_before_discount',
        'price_after_discount',
        'active_period',
        'period_type',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Module')
            ->setDescriptionForEvent(fn(string $eventName) => "Module model has been {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class);
    }

    public function detailModule()
    {
        return $this->hasMany(DetailModule::class);
    }

    public function userAccess()
    {
        return $this->morphMany(UserAccess::class, 'access')
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->whereNull('expired_date')
                    ->orWhere('expired_date', '>', Carbon::now());
            });
    }
}
