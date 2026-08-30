<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ReferralLink extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id', 
        'referral_code'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('ReferralLink')
            ->setDescriptionForEvent(fn(string $eventName) => "ReferralLink model has been {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}