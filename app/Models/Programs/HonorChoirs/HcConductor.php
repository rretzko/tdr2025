<?php

namespace App\Models\Programs\HonorChoirs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcConductor extends Model
{
    /** @use HasFactory<\Database\Factories\Programs\HonorChoirs\HcConductorFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
    ];
}
