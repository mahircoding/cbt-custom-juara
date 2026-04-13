<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\User;
use App\Models\Affiliate\UserBank;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Withdrawal extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'user_bank_id',
        'total_withdrawal',
        'admin_fee',
        'total_paid',
        'date_approved',
        'file',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Withdrawal')
            ->setDescriptionForEvent(fn(string $eventName) => "Withdrawal model has been {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userBank()
    {
        return $this->belongsTo(UserBank::class);
    }
}
