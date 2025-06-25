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

    public array $participatingClassOfsSelectedIds = [];
    public array $participatingSchoolsSelectedIds = [];
    public array $participatingVoicePartsSelectedIds = [];

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

        //build filters as needed by the respective header
        if (!$this->libraryFiltersBuilt()) {
            $versionSeniorClass = Version::find($this->versionId)->senior_class_of ?? 0;

            if ($this->versionId && in_array($this->header, ['candidates', 'candidates table', 'participation results'])) {

                //initially set candidateGrades filter to include ALL candidate grades
                $this->candidateGradesSelectedIds = Candidate::query()
                    ->join('students', 'students.id', '=', 'candidates.student_id')
                    ->where('version_id', $this->versionId)
                    ->where('students.class_of', '>=', $versionSeniorClass)
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

            } elseif ($this->header === 'ensembles') {

                //initially set schools filter to include ALL schools
                $this->schoolsSelectedIds = $this->setSchoolsSelectedIds();

                //initially set ensembles filter to include ALL schools' ensembles
                foreach (auth()->user()->teacher->schools as $school) {
                    foreach ($school->ensembles as $ensemble) {
                        $this->ensemblesSelectedIds[] = $ensemble->id;
                    }
                }

                //initially set ensembleYears filter to include ALL ensembles' school years
                $this->ensembleYearsSelectedIds = array_values($this->ensembleYears());

            } elseif ($this->header === 'members') {

                //initially set schools filter to include ALL schools
                $this->schoolsSelectedIds = $this->setSchoolsSelectedIds();

                //initially set ensembles filter to include ALL schools' ensembles
                foreach (auth()->user()->teacher->schools as $school) {
                    foreach ($school->ensembles as $ensemble) {
                        $this->ensemblesSelectedIds[] = $ensemble->id;
                    }
                }

                //initially set ensembleYears filter to include ALL ensembles' school years
                $this->ensembleYearsSelectedIds = array_values($this->ensembleYears());

            } elseif (
                ($this->header === 'participating students') ||
                ($this->header === 'student counts')
            ) {

                $this->participatingClassOfsSelectedIds = array_keys($this->participatingClassOfs());
                $this->participatingSchoolsSelectedIds = array_keys($this->participatingSchools());
                $this->participatingVoicePartsSelectedIds = array_keys($this->participatingVoiceParts());

            } elseif ($this->header === 'school edit') {

                logger($this->header . ' found; no filters.');

            } elseif ($this->header === 'students') {

                //initially set schools filter to include ALL schools
                $this->schoolsSelectedIds = $this->setSchoolsSelectedIds();

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

            } elseif (in_array($this->header, ['pitchFiles', 'teacher pitch files', 'version pitch files'])) {

                //initially set pitchFileVoiceParts filter to include ALL voicePartIds for pitch files
                $voiceParts = VersionPitchFile::query()
                    ->join('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
                    ->where('version_pitch_files.version_id', UserConfig::getValue('versionId'))
                    ->distinct('version_pitch_files.voice_part_id')
                    ->select('voice_parts.id', 'voice_parts.order_by')
                    ->orderBy('voice_parts.order_by')
                    ->pluck('voice_parts.id')
                    ->toArray();

                //            Log::info(serialize($voiceParts));

                $this->pitchFileVoicePartsSelectedIds = $voiceParts;

                //initially set pitchFileFileTypes filter to include all file types for pitch files
                $fileTypes = VersionPitchFile::query()
                    ->where('version_pitch_files.version_id', UserConfig::getValue('versionId'))
                    ->distinct('version_pitch_files.file_type')
                    ->orderBy('version_pitch_files.file_type')
                    ->pluck('version_pitch_files.file_type', 'version_pitch_files.file_type')
                    ->toArray();

                $this->pitchFileFileTypesSelectedIds = $fileTypes;

            } else {

                //            Log::info(__METHOD__.': '.__LINE__);
                //            Log::info('no filters workflow for header: '.$this->header);
            }
        }

    }

    public function apply($query)
    {
        return $query->whereIn('ensembles.school_id', $this->schoolsSelectedIds);
    }

    public function candidateGrades(): array
    {
        $teacherIds = CoTeachersService::getCoTeachersIds();
        $versionSeniorClass = Version::find($this->versionId)->senior_class_of ?? 0;

        $classOfs = [];
        if ($versionSeniorClass) {
            $classOfs = Candidate::query()
                ->join('students', 'students.id', '=', 'candidates.student_id')
                ->whereIn('candidates.teacher_id', $teacherIds)
                ->where('version_id', $this->versionId)
                ->where('students.class_of', '>=', $versionSeniorClass)
                ->distinct('students.class_of')
                ->orderByDesc('students.class_of')
                ->pluck('students.class_of')
                ->toArray();
        }

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

    public function pitchFileVoiceParts(): array
    {
        $versionId = UserConfig::getValue('versionId');

        return (VersionPitchFile::where('version_id', $versionId)->exists())
            ? VersionPitchFile::where('version_id', $versionId)
                ->join('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
                ->select('voice_parts.id', 'voice_parts.order_by', 'voice_parts.descr')
                ->distinct('voice_parts.id')
                ->orderBy('voice_parts.order_by')
                ->pluck('voice_parts.descr', 'voice_parts.id')
                ->toArray()
            : [];
    }

    public function filterCandidatesByClassOfs($query)
    {
        //early exit
        if (!$this->candidateGradesSelectedIds) {
            return $query;
        }

        return $query->whereIn('students.class_of', $this->candidateGradesSelectedIds);
    }

    public function filterCandidatesByParticipatingClassOfs($query)
    {
        return $query->whereIn('students.class_of', $this->participatingClassOfsSelectedIds);
    }

    public function filterCandidatesByParticipatingSchools($query)
    {
        $participatingSchoolIds = array_keys($this->participatingSchools());

        return $query->whereIn('candidates.school_id', $participatingSchoolIds);
    }

    public function filterCandidatesByParticipatingVoiceParts($query)
    {
        return $query->whereIn('candidates.voice_part_id', $this->participatingVoicePartsSelectedIds);
    }

    public function filterCandidatesByStatuses($query)
    {
        //early exit
        if (!$this->candidateStatusesSelectedIds) {
            return $query;
        }

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
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
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

    public function participatingClassOfs(): array
    {
        $basePage = new BasePage();

        return $basePage->getParticipatingClassOfs();
    }

    public function participatingSchools(): array
    {
        $basePage = new BasePage();

        return $basePage->getParticipatingSchools();
    }

    public function participatingVoiceParts(): array
    {
        $basePage = new BasePage();

        return $basePage->getParticipatingVoiceParts();
    }

    public function schools(): array
    {
        return auth()->user()->teacher->schools->pluck('abbr', 'id')->toArray();
    }

    public function setFilter(string $filter, string $header): void
    {
        $str = '';

        $str = implode(',', $this->$filter);

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
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));

        if (in_array('current', $this->classOfsSelectedIds) ||
            in_array('alum', $this->classOfsSelectedIds)) {
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
            $service = new CalcSeniorYearService();
            $srYear = $service->getSeniorYear();

            (in_array('alum', $this->classOfsSelectedIds))
                ? $this->interpretAggregateClassOfValuesAlum($srYear)
                : $this->interpretAggregateClassOfValuesCurrent($srYear);
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
        }
    }

    private function interpretAggregateClassOfValuesAlum(int $srYear): void
    {
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
        $this->reset('classOfsSelectedIds');

        foreach ($this->getTeacherClassOfs() as $classOf) {

            if ($classOf < $srYear) {

                $this->classOfsSelectedIds[] = $classOf;
            }
        }
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
    }

    private function interpretAggregateClassOfValuesCurrent(int $srYear): void
    {
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
        $this->reset('classOfsSelectedIds');

        foreach ($this->getTeacherClassOfs() as $classOf) {

            if ($classOf >= $srYear) {

                $this->classOfsSelectedIds[] = $classOf;
            }
        }
//Log::info(__METHOD__ . ': ' . __LINE__);
//Log::info(implode(' | ' , $this->classOfsSelectedIds));
    }

    private function libraryFiltersBuilt(): bool
    {
        $libraryHeaders = ['libraries', 'library item', 'library items'];
        if (in_array($this->header, $libraryHeaders)) {
            //build Library filters
            return true;
        }

        return false;
    }

    private function setSchoolsSelectedIds(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('id')
            ->toArray();
    }
}
