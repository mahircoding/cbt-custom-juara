<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseDetail;
use App\Traits\Uuid;
use App\Models\MemberCategory;
use App\Models\MasterData\Category;
use App\Models\User;
use App\Models\Course\CourseGoal;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Course extends Model
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
        'video_intro',
        'prerequisite',
        'sort_description',
        'description',
        'order',
        'price_type', // 1 free, 2 paid
        'price_before_discount',
        'price_after_discount',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Course')
            ->setDescriptionForEvent(fn(string $eventName) => "Course model has been {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function courseDetail()
    {
        return $this->hasMany(CourseDetail::class);
    }

    public function memberCategories()
    {
        return $this->belongsToMany(MemberCategory::class)->withTimestamps();
    }

    public function courseGoal()
    {
        return $this->hasMany(CourseGoal::class);
    }
}
