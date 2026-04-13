<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupBalance extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('empty', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->whereRaw('1 = 0');
        });
    }
}
