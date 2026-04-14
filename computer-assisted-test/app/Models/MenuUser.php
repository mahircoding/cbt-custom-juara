<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuUser extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'description',
        'order',
        'is_active',
        'type',
    ];
}
