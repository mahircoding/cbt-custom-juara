<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Transaction\Transaction;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VoucherCode extends Model
{
    use HasFactory, Uuid, LogsActivity;

    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'code',
        'nominal_voucher',
        'user_limit',
        'expired_date',
        'is_active',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('VoucherCode');
    }

    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'item')->withTrashed();
    }
}
