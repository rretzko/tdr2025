<?php

namespace App\Models\Schools;

use App\Models\County;
use App\Models\Ensembles\Ensemble;
use App\Models\Geostate;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'abbr',
        'name',
        'postal_code',
        'city',
        'county_id',
    ];

    public function getAddressAttribute(): string
    {
        return $this->city.' in '.$this->getCountyNameAttribute().', '.$this->postal_code;
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function ensembles(): HasMany
    {
        return $this->hasMany(Ensemble::class);
    }

    public function getCountyNameAttribute(): string
    {
        return County::find($this->county_id)->name;
    }

    public function getGeostateAbbrAttribute(): string
    {
        return Geostate::find(County::find($this->county_id)->geostate_id)->abbr;
    }

    public function getGradesAttribute(): array
    {
        return SchoolGrade::query()
            ->where('school_id', $this->id)
            ->orderBy('grade')
            ->pluck('grade')
            ->toArray();
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function updateGrades(array $grades): void
    {
        //clear the table of $this grades
        DB::table('school_grades')
            ->where('school_id', $this->id)
            ->delete();

        foreach ($grades as $grade) {

            SchoolGrade::create(
                [
                    'school_id' => $this->id,
                    'grade' => $grade,
                ]
            );
        }
    }
}
