<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Lesson\ValueCategory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DetailValueCategory extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'value_category_id',
        'min_grade',
        'max_grade',
        'category_grade',
        'final_grade',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('DetailValueCategory')
            ->setDescriptionForEvent(fn(string $eventName) => "DetailValueCategory model has been {$eventName}");
    }
    
    public function valueCategory()
    {
        return $this->belongsTo(ValueCategory::class);
    }
}
