<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\SubCategory;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroup;
use App\Models\Material\Module;
use App\Models\Material\VideoModule;
use App\Models\Course\Course;
use App\Models\Material\Classroom;
use App\Models\Lesson\ValueCategoryGroup;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'thumbnail',
        'order',
        'development_status',
        // transaction by category
        'enable_practice_question_sales',
        'practice_question_sales_type',
        'enable_tryout_sales',
        'tryout_sales_type',
        'enable_module_material_sales',
        'module_material_sales_type',
        'enable_video_module_sales',
        'video_module_sales_type',
        'enable_course_sales',
        'course_sales_type',
        'enable_classroom_sales',
        'classroom_sales_type',
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Category')
            ->setDescriptionForEvent(fn(string $eventName) => "Category model has been {$eventName}");
    }
    
    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }

    public function examGroup()
    {
        return $this->hasMany(ExamGroup::class);
    }

    public function module()
    {
        return $this->hasMany(Module::class);
    }

    public function videoModule()
    {
        return $this->hasMany(VideoModule::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function classroom()
    {
        return $this->hasMany(Classroom::class);
    }

    public function valueCategoryGroup()
    {
        return $this->hasMany(ValueCategoryGroup::class);
    }
}
