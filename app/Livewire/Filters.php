<?php

namespace App\Livewire;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Students\VoicePart;
use App\Models\UserFilter;
use App\Services\CalcSeniorYearService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Form;

class Filters extends Form
{
    #[Url]
    public array $classOfsSelectedIds = [];
    #[Url]
    public array $ensemblesSelectedIds = [];
    public array $ensembleYearsSelectedIds = [];
    public string $header = '';
    #[Url]
    public array $schoolsSelectedIds = [];
    #[Url]
    public array $voicePartIdsSelectedIds = [];

    public function init(string $header)
    {
        $this->header = $header;

        //initially set ensembles filter to include ALL schools' ensembles
        foreach (auth()->user()->teacher->schools as $school) {
            foreach ($school->ensembles as $ensemble) {
                $this->ensemblesSelectedIds[] = $ensemble->id;
            }
        }

        //initially set ensembleYears filter to include ALL ensembles' school years
        $this->ensembleYearsSelectedIds = array_values($this->ensembleYears());

        //initially set classOfs filter to include ALL classOfs for auth()->user()->teacher
        $this->classOfsSelectedIds = auth()->user()->teacher->students
            ->unique('class_of')
            ->sortByDesc('class_of')
            ->pluck('class_of')
            ->toArray();

        //initially set schools filter to include ALL schools
        $this->schoolsSelectedIds = auth()->user()->teacher->schools
            ->pluck('id')
            ->toArray();

        //initially set voicePartIds filter to include ALL voicePartIds for auth()->user()->teacher
        $this->voicePartIdsSelectedIds = auth()->user()->teacher->students
            ->unique('voice_part_id')
            ->sortByDesc('voice_part_id')
            ->pluck('voice_part_id')
            ->toArray();

    }

    public function apply($query)
    {
        return $query->whereIn('ensembles.school_id', $this->schoolsSelectedIds);
    }

    public function classOfs(): array
    {
        return auth()->user()->teacher->students
            ->unique('class_of')
            ->sortByDesc('class_of')
            ->pluck('class_of', 'class_of')
            ->toArray();
    }

    public function ensembles(): array
    {
        $a = [];

        foreach (auth()->user()->teacher->schools as $school) {

            foreach ($school->ensembles as $ensemble) {

                $a[$ensemble->id] = $ensemble->abbr;
            }
        }

        return $a;
    }

    public function ensembleYears(): array
    {
        $schoolIds = auth()->user()->teacher->schools->pluck('id')->toArray();
        $ensembleIds = Ensemble::whereIn('school_id', $schoolIds)->pluck('id')->toArray();

        return Member::query()
            ->whereIn('ensemble_id', $ensembleIds)
            ->distinct('school_year')
            ->pluck('school_year', 'school_year')
            ->toArray();
    }

    public function filterStudentsByClassOfs($query)
    {
        $this->interpretAggregateClassOfValues();

        return $query->whereIn('students.class_of', $this->classOfsSelectedIds);
    }

    public function filterStudentsBySchools($query)
    {
        return $query->whereIn('school_student.school_id', $this->schoolsSelectedIds);
    }

    public function filterStudentsByVoicePartIds($query)
    {
        return $query->whereIn('students.voice_part_id', $this->voicePartIdsSelectedIds);
    }

    public function filterMembersByEnsemble($query)
    {
        return $query->whereIn('ensemble_members.ensemble_id', $this->ensemblesSelectedIds);
    }

    public function filterMembersBySchoolYear($query)
    {
        return $query->whereIn('ensemble_members.school_year', $this->ensembleYearsSelectedIds);
    }

    public function getPreviousFilterArray(string $filter, string $header): array
    {
        $row = UserFilter::query()
            ->where('user_id', auth()->id())
            ->where('header', $header)
            ->where('filter', $filter)
            ->first();

        return $row && strlen($row->values) ? explode(',', $row->values) : [];
    }

    public function getTeacherClassOfs(): array
    {
        return auth()->user()->teacher->students
            ->sortByDesc('class_of')
            ->unique('class_of')
            ->pluck('class_of', 'class_of')
            ->toArray();
    }

    public function schools(): array
    {
        return auth()->user()->teacher->schools->pluck('abbr', 'id')->toArray();
    }

    public function setFilter(string $filter, string $header): void
    {
        $str = '';

        $str = implode(',', $this->$filter);
        Log::info('string: '.$str);
        UserFilter::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $header,
                'filter' => $filter,
            ],
            [
                'values' => $str,
            ]
        );
    }

    /**
     * Student voice part ids
     * @return array
     */
    public function voicePartIds(): array
    {
        $voicePartIds = auth()->user()->teacher->students
            ->unique('voice_part_id')
            ->sortBy('voice_part_id')
            ->pluck('voice_part_id', 'voice_part_id')
            ->toArray();

        $voiceParts = VoicePart::find($voicePartIds);

        return $voiceParts->sortBy('order_by')->pluck('abbr', 'id')->toArray();
    }

    public function previousFilterExists(string $filter, string $header): bool
    {
        return UserFilter::query()
            ->where('user_id', auth()->id())
            ->where('header', $header)
            ->where('filter', $filter)
            ->exists();
    }

    private function interpretAggregateClassOfValues(): void
    {
        if (in_array('current', $this->classOfsSelectedIds) ||
            in_array('alum', $this->classOfsSelectedIds)) {

            $service = new CalcSeniorYearService();
            $srYear = $service->getSeniorYear();

            (in_array('alum', $this->classOfsSelectedIds))
                ? $this->interpretAggregateClassOfValuesAlum($srYear)
                : $this->interpretAggregateClassOfValuesCurrent($srYear);
        }
    }

    private function interpretAggregateClassOfValuesAlum(int $srYear): void
    {
        $this->reset('classOfsSelectedIds');

        foreach ($this->getTeacherClassOfs() as $classOf) {

            if ($classOf < $srYear) {

                $this->classOfsSelectedIds[] = $classOf;
            }
        }
    }

    private function interpretAggregateClassOfValuesCurrent(int $srYear): void
    {
        $this->reset('classOfsSelectedIds');

        foreach ($this->getTeacherClassOfs() as $classOf) {

            if ($classOf >= $srYear) {

                $this->classOfsSelectedIds[] = $classOf;
            }
        }
    }
}
