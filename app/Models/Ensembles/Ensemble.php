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

    public function countCurrentMembers(): int
    {
        $service = new CalcSeniorYearService();

        return Member::query()
            ->where('ensemble_id', $this->id)
            ->where('status', 'active')
            ->where('school_year', $service->getSeniorYear())
            ->count('id');
    }

    public function countLifetimeMembers(): int
    {
        return Member::query()
            ->where('ensemble_id', $this->id)
            ->distinct('student_id')
            ->count('student_id');
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

    public function allStatusStudentIdsArray($schoolYear): array
    {
        return Member::query()
            ->withTrashed()
            ->where('ensemble_id', $this->id)
            ->where('school_year', $schoolYear)
            ->pluck('student_id')
            ->toArray();
    }
}
