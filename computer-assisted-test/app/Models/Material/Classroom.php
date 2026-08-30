<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\Uuid;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\MemberCategory;
use App\Models\UserAccess;
use Auth;

class Classroom extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'category_id',
        'user_id',
        'name',
        'title',
        'material',
        'start_time',
        'duration',
        'description',
        'link',
        'price_type',
        'price_before_discount',
        'price_after_discount',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Classroom')
            ->setDescriptionForEvent(fn(string $eventName) => "Classroom model has been {$eventName}");
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class);
    }

    public function userAccess()
    {
        return $this->morphMany(UserAccess::class, 'access')->where('user_id', Auth::user()->id);
    }
}
