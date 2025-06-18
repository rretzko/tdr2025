<?php

namespace App\Models\Programs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramAddendum extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_selection_id',
        'addendum',
    ];
}
