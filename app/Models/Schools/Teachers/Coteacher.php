<?php

namespace App\Models\Schools\Teachers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coteacher extends Model
{
    use HasFactory;

    protected $fillable = ['coteacher_id', 'school_id', 'teacher_id'];
}
