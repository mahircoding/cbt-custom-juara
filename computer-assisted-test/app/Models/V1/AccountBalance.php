<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $connection = 'mysql_V1';
    
    protected $fillable = [
        'is_migrated'
    ];
}
