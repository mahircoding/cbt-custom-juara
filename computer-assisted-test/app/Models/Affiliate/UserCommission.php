<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserCommission extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('UserCommission')
            ->setDescriptionForEvent(fn(string $eventName) => "UserCommission model has been {$eventName}");
    }

    protected $fillable = [
        'id',
        'user_id',
        'total_commission',
        'total_withdrawals',
        'current_balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
