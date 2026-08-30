<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MasterData\Category;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SubCategory extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'order'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('SubCategory')
            ->setDescriptionForEvent(fn(string $eventName) => "SubCategory model has been {$eventName}");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
