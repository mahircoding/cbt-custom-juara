<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\Category;
use App\Models\Lesson\Lesson;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LessonCategory extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'thumbnail',
        'order',
        'development_status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('LessonCategory')
            ->setDescriptionForEvent(fn(string $eventName) => "LessonCategory model has been {$eventName}");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lesson()
    {
        return $this->hasMany(Lesson::class);
    }
}
