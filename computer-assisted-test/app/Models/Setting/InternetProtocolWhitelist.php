<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InternetProtocolWhitelist extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'ip',
        'name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('InternetProtocolWhitelist')
            ->setDescriptionForEvent(fn(string $eventName) => "InternetProtocolWhitelist model has been {$eventName}");
    }
}
