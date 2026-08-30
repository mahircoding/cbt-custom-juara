<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserBank extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('UserBank')
            ->setDescriptionForEvent(fn(string $eventName) => "UserBank model has been {$eventName}");
    }

    protected $fillable = [
        'id',
        'user_id',
        'bank_name',
        'account_number',
        'account_name',
    ];
}
