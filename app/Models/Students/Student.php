<?php

namespace App\Models\Students;

use App\Models\Address;
use App\Models\EmergencyContact;
use App\Models\PhoneNumber;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\User;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcGradeFromClassOfService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'voice_part_id',
        'class_of',
        'height',
        'birthday',
        'shirt_size'
    ];

    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'user_id', 'user_id');
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function getGradeAttribute(): int
    {
        $service = new CalcGradeFromClassOfService();

        return $service->getGrade($this->class_of);
    }

    public function getPhoneHomeAttribute(): string
    {
        return PhoneNumber::query()
            ->where('user_id', $this->user_id)
            ->where('phone_type', 'home')
            ->value('phone_number') ?? '';
    }

    public function getPhoneMobileAttribute(): string
    {
        return PhoneNumber::query()
            ->where('user_id', $this->user_id)
            ->where('phone_type', 'mobile')
            ->value('phone_number') ?? '';
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class)
            ->withPivot('active');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voicePart(): BelongsTo
    {
        return $this->belongsTo(VoicePart::class);
    }
}
