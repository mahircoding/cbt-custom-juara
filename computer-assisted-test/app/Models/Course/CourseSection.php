<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseDetail;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CourseSection extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'course_id',
        'title',
        'order',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('CourseSection')
            ->setDescriptionForEvent(fn(string $eventName) => "CourseSection model has been {$eventName}");
    }

    public function courseDetail()
    {
        return $this->hasMany(CourseDetail::class);
    }
}
