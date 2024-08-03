<?php

namespace App\Livewire;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionPitchFile;
use App\Models\Students\VoicePart;
use App\Models\User;
use App\Models\UserConfig;
use App\Models\UserFilter;
use App\Services\CalcGradeFromClassOfService;
use App\Services\CalcSeniorYearService;
use App\Services\CoTeachersService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Form;

class Filters extends Form
{
    #[Url]
    public array $candidateGradesSelectedIds = [];
    #[Url]
    public array $candidateStatusesSelectedIds = [];
    #[Url]
    public array $classOfsSelectedIds = [];
    #[Url]
    public array $ensemblesSelectedIds = [];
    public array $ensembleYearsSelectedIds = [];
    public string $header = '';
    #[Url]
    public array $pitchFileFileTypesSelectedIds = [];
    #[Url]
    public array $pitchFileVoicePartsSelectedIds = [];
    #[Url]
    public array $schoolsSelectedIds = [];
    public int $versionId = 0;
    #[Url]
    public array $voicePartIdsSelectedIds = [];


    public function init(string $header)
    {
        $this->header = $header;
        $this->versionId = (int) UserConfig::getValue('versionId');

        if ($this->header === 'schools') {

        } elseif ($this->header === 'students') {

            //initially set schools filter to include ALL schools
            $this->schoolsSelectedIds = auth()->user()->teacher->schools
                ->pluck('id')
                ->toArray();

            //initially set classOfs filter to include ALL classOfs for auth()->user()->teacher
            $this->classOfsSelectedIds = auth()->user()->teacher->students
                ->unique('class_of')
                ->sortByDesc('class_of')
                ->pluck('class_of')
                ->toArray();

            //initially set voicePartIds filter to include ALL voicePartIds for auth()->user()->teacher
            $this->voicePartIdsSelectedIds = auth()->user()->teacher->students
                ->unique('voice_part_id')
                ->sortByDesc('voice_part_id')
                ->pluck('voice_part_id')
                ->toArray();
        } elseif ($this->header === 'candidates') {

            //initially set candidateGrades filter to include ALL candidate grades
            $this->candidateGradesSelectedIds = Candidate::query()
                ->join('students', 'students.id', '=', 'candidates.student_id')
                ->where('version_id', $this->versionId)
                ->distinct('students.class_of')
                ->orderBy('students.class_of')
                ->pluck('students.class_of')
                ->toArray();

            //initially set candidateStatuses filter to include ALL candidate statuses
            $this->candidateStatusesSelectedIds = Candidate::query()
                ->where('version_id', $this->versionId)
                ->distinct('status')
                ->orderBy('status')
                ->pluck('status')
                ->toArray();

        } else {

            //initially set ensembles filter to include ALL schools' ensembles
            foreach (auth()->user()->teacher->schools as $school) {
                foreach ($school->ensembles as $ensemble) {
                    $this->ensemblesSelectedIds[] = $ensemble->id;
                }
            }

            //initially set ensembleYears filter to include ALL ensembles' school years
            $this->ensembleYearsSelectedIds = array_values($this->ensembleYears());

            //initially set pitchFileVoiceParts filter to include ALL voicePartIds for pitch files
            $this->pitchFileVoicePartsSelectedIds = VersionPitchFile::query()
                ->join('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
                ->where('version_pitch_files.version_id', UserConfig::getValue('versionId'))
                ->distinct('version_pitch_files.voice_part_id')
                ->orderBy('voice_parts.order_by')
                ->pluck('voice_parts.id')
                ->toArray();

            //initially set pitchFileFileTypes filter to include all file types for pitch files
            $this->pitchFileFileTypesSelectedIds = VersionPitchFile::query()
                ->where('version_pitch_files.version_id', UserConfig::getValue('versionId'))
                ->distinct('version_pitch_files.file_type')
                ->orderBy('version_pitch_files.file_type')
                ->pluck('version_pitch_files.file_type', 'version_pitch_files.file_type')
                ->toArray();

        }

    }

    public function apply($query)
    {
        return $query->whereIn('ensembles.school_id', $this->schoolsSelectedIds);
    }

    public function candidateGrades(): array
    {
        $teacherIds = CoTeachersService::getCoTeachersIds();

        $classOfs = Candidate::query()
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->whereIn('candidates.teacher_id', $teacherIds)
            ->where('version_id', $this->versionId)
            ->distinct('students.class_of')
            ->orderByDesc('students.class_of')
            ->pluck('students.class_of')
            ->toArray();

        $serviceSeniorYear = new CalcSeniorYearService;
        $seniorYear = $serviceSeniorYear->getSeniorYear();

        $serviceGrade = new CalcGradeFromClassOfService;

        return array_combine(
            $classOfs,
            array_map(function ($classOf) use ($serviceGrade) {
                return $serviceGrade->getGrade($classOf);
            }, $classOfs)
        );
    }

    public function candidateStatuses()
    {
        return Candidate::query()
            ->where('version_id', $this->versionId)
            ->distinct('status')
            ->orderBy('status')
            ->pluck('status', 'status')
            ->toArray();
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

    public function pitchFileVoiceParts(): array
    {
        $versionId = UserConfig::getValue('versionId');

        return (VersionPitchFile::where('version_id', $versionId)->exists())
            ? VersionPitchFile::where('version_id', $versionId)
                ->join('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
                ->distinct('voice_parts.id')
                ->orderBy('voice_parts.order_by')
                ->pluck('voice_parts.descr', 'voice_parts.id')
                ->toArray()
            : [];
    }

    public function pitchFileFileTypes(): array
    {
        $versionId = UserConfig::getValue('versionId');

        return (VersionPitchFile::where('version_id', $versionId)->exists())
            ? VersionPitchFile::where('version_id', $versionId)
                ->distinct('version_pitch_files.file_type')
                ->orderBy('version_pitch_files.file_type')
                ->pluck('version_pitch_files.file_type', 'version_pitch_files.file_type')
                ->toArray()
            : [];
    }

    public function filterCandidatesByClassOfs($query)
    {
        return $query->whereIn('students.class_of', $this->candidateGradesSelectedIds);
    }

    public function filterCandidatesByStatuses($query)
    {
        return $query->whereIn('candidates.status', $this->candidateStatusesSelectedIds);
    }

    public function filterPitchFileFileTypes($query)
    {
        return $query->whereIn('version_pitch_files.file_type', $this->pitchFileFileTypesSelectedIds);
    }

    public function filterPitchFileVoiceParts($query)
    {
        return $query->whereIn('voice_part_id', $this->pitchFileVoicePartsSelectedIds);
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

    public function getPitchFileVoicePartIds(): array
    {
        $a = [];
        $ensembles = Event::find(UserConfig::getValue('eventId'))->eventEnsembles;

        foreach ($ensembles as $ensemble) {

            $a = array_merge($a, explode(',', $ensemble->voice_part_ids));
        }

        return VoicePart::whereIn('id', $a)
            ->distinct('id')
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
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
            ->whereNot('values', '')
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
