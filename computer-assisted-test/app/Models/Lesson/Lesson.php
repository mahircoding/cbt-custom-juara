<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Lesson\LessonCategory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Lesson extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'lesson_category_id',
        'name',
        'short_name',
        'thumbnail',
        'order',
        'development_status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Lesson')
            ->setDescriptionForEvent(fn(string $eventName) => "Lesson model has been {$eventName}");
    }

    public function lessonCategory()
    {
        return $this->belongsTo(LessonCategory::class);
    }
}
