<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Material\VideoModule;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DetailVideoModule extends Model
{
    use HasFactory, Uuid, LogsActivity;
    
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'video_module_id',
        'title',
        'link',
        'description',
        'order',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('DetailVideoModule')
            ->setDescriptionForEvent(fn(string $eventName) => "DetailVideoModule model has been {$eventName}");
    }
}
