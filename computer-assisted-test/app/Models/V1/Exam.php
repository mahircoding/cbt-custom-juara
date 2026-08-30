<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $connection = 'mysql_V1';
    
    protected $fillable = [
        'member_categories'
    ];
}
