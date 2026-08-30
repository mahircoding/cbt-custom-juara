<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Commission extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'transaction_id',
        'user_id',
        'commission_type',
        'commission',
        'amount',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Commission')
            ->setDescriptionForEvent(fn(string $eventName) => "Commission model has been {$eventName}");
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
