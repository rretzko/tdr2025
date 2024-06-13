<?php

namespace App\Models\Ensembles;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'user_id',
    ];
}
