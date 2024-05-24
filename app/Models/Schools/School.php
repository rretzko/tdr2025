<?php

namespace App\Models\Schools;

use App\Models\County;
use App\Models\Geostate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postal_code',
        'city',
        'county_id',
    ];

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function getCountyNameAttribute(): string
    {
        return County::find($this->county_id)->name;
    }

    public function getGeostateAbbrAttribute(): string
    {
        return Geostate::find(County::find($this->county_id)->geostate_id)->abbr;
    }

    public function getGradesAttributes(): array
    {
        return SchoolGrade::query()
            ->where('school_id', $this->id)
            ->orderBy('grade')
            ->pluck('grade')
            ->toArray();
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
