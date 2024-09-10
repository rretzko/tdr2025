<?php

namespace App\Models\Events\Versions;

use App\Models\Schools\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'school_id',
        'user_id',
        'fee_type',
        'transaction_id',
        'amount',
        'comments',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
