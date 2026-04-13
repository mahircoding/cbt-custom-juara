<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PaymentMethod extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'code';

    public $incrementing = false;
    
    protected $fillable = [
        'code',
        'name',
        'description',
        'show_description',
        'is_active',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('PaymentMethod');
    }
}
