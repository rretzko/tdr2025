<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'grade',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
