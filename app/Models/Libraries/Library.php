<?php

namespace App\Models\Libraries;

use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Library extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'name',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function user(): User
    {
        return User::find($this->teacher->user_id);
    }
}
