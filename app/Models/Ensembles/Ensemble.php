<?php

namespace App\Models\Ensembles;

use App\Models\Ensembles\Members\Member;
use App\Models\Schools\School;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcSeniorYearService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Ensemble extends Model
{
    use hasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'short_name',
        'abbr',
        'description',
        'active',
        'grades',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function countActiveMembers(): int
    {
        return Member::query()
            ->where('ensemble_id', $this->id)
            ->where('status', 'active')
            ->count('id');
    }

    public function countNonActiveMembers(): int
    {
        return Member::query()
            ->where('ensemble_id', $this->id)
            ->whereNot('status', 'active')
            ->count('id');
    }

    public function classOfsArray(int $srYear): array
    {
        $service = new CalcClassOfFromGradeService();
        $grades = explode(',', $this->grades);

        $classOfs = [];
        foreach ($grades as $grade) {
            $classOfs[] = $service->getClassOf($grade);
        }

        return $classOfs;
    }

    public function activeMembers(int $schoolYear): Collection
    {
        return Member::query()
            ->where('ensemble_id', $this->id)
            ->where('school_year', $schoolYear)
            ->where('status', 'active')
            ->get();
    }

    public function activeMemberStudentIdsArray($schoolYear): array
    {
        return $this->activeMembers($schoolYear)
            ->pluck('student_id')
            ->toArray();
    }
}
