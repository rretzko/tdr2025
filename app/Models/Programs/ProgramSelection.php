<?php

namespace App\Models\Programs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'lib_item_id',
        'ensemble_id',
        'order_by'
    ];
}
