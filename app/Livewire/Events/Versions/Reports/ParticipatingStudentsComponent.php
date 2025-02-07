<?php

namespace App\Livewire\Events\Versions\Reports;


use App\Exports\ParticipatingStudentsExport;
use App\Livewire\Forms\RoomForm;
use App\Livewire\Forms\StudentForm;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\PhoneNumber;
use App\Models\Students\Student;
use App\Services\FormatPhoneService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Can;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;

class ParticipatingStudentsComponent extends BasePageReports
{
    public StudentForm $form;
    public array $columnHeaders = [];
    public Collection $eventVoiceParts;
    public array $summaryColumnHeaders = [];

    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = true;
        $this->columnHeaders = $this->getColumnHeaders();
        $this->summaryColumnHeaders = $this->getSummaryColumnHeaders();

        //sorts
        $this->sortCol = $this->userSort ? $this->userSort->column : 'schoolName';
        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'school';

        //filters
        $this->filters->participatingClassOfsSelectedIds = $this->filters->previousFilterExists('participatingClassOfsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('participatingClassOfsSelectedIds', $this->dto['header'])
            : $this->filters->participatingClassOfsSelectedIds;

        $this->filters->participatingSchoolsSelectedIds = $this->filters->previousFilterExists('participatingSchoolsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('participatingSchoolsSelectedIds', $this->dto['header'])
            : $this->filters->participatingSchoolsSelectedIds;

        $this->filters->participatingVoicePartsSelectedIds = $this->filters->previousFilterExists('participatingVoicePartsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('participatingVoicePartsSelectedIds', $this->dto['header'])
            : $this->filters->participatingVoicePartsSelectedIds;

        //filterMethods
        if (count($this->filters->participatingSchoolsSelectedIds) > 1) {
            $this->filterMethods[] = 'participatingSchools';
        }
        if (count($this->filters->participatingClassOfsSelectedIds) > 1) {
            $this->filterMethods[] = 'participatingClassOfs';
        }
        if (count($this->filters->participatingVoicePartsSelectedIds) > 1) {
            $this->filterMethods[] = 'participatingVoiceParts';
        }

        //eventVoiceParts
        $this->eventVoiceParts = $this->version->event->voiceParts;
    }

    public function clickEdit(int $candidateId): void
    {
        $this->form->setStudentForRegistrationManager($candidateId);
    }

    #[NoReturn] public function saveEdits(): void
    {
        $validated = $this->validate([
            'form.first' => ['required'],
            'form.last' => ['required'],
        ]);

        $student = Student::find($this->form->studentId);
        $user = $student->user;
        $candidate = Candidate::where('student_id', $student->id)
            ->where('version_id', $this->versionId)
            ->first();

        $user->first_name = $this->form->first;
        $user->middle_name = $this->form->middle;
        $user->last_name = $this->form->last;
        $fullName = $this->makeFullName();
        $user->name = $fullName;

        $user->save();

        $candidate->program_name = $fullName;
        $candidate->voice_part_id = $this->form->voicePartId;
        $candidate->save();

        $this->addEditPhoneNumber($this->form->phoneHome, 'home', $user->id);
        $this->addEditPhoneNumber($this->form->phoneMobile, 'mobile', $user->id);

        //clear vars
        $this->form->first = '';
        $this->form->middle = '';
        $this->form->last = '';
        $this->form->voicePartId = 1;
        $this->form->phoneHome = '';
        $this->form->phoneMobile = '';
        $this->form->studentId = 0;
    }

    private function addEditPhoneNumber(string $phoneNumber, string $phoneType, int $userId): void
    {
        $phoneService = new FormatPhoneService();
        $fPhoneNumber = $phoneService->getPhoneNumber($phoneNumber);
        $phoneExists = PhoneNumber::query()
            ->where('user_id', $userId)
            ->where('phone_type', $phoneType)
            ->first();
        if ($phoneExists) {
            $phoneExists->phone_number = $fPhoneNumber;
            $phoneExists->save();
        } else {
            if ($fPhoneNumber) {
                $new = new PhoneNumber();
                $new->user_id = $userId;
                $new->phone_type = $phoneType;
                $new->phone_number = $fPhoneNumber;
                $new->save();
            }
        }
    }


    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'teacher'], //users.last_name
            ['label' => 'registrant', 'sortBy' => 'registrant'],
            ['label' => 'grade', 'sortBy' => 'classOf'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
        ];
    }

    private function getCountOfRegistrants(int $voicePartId): int
    {
        return Candidate::query()
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where('candidates.voice_part_id', $voicePartId)
            ->whereIn('candidates.school_id', $this->filters->participatingSchoolsSelectedIds)
            ->whereIn('students.class_of', $this->filters->participatingClassOfsSelectedIds)
            ->whereIn('candidates.voice_part_id', $this->filters->participatingVoicePartsSelectedIds)
            ->count('candidates.id');
    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('participatingSchoolsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('participatingClassOfsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('participatingVoicePartsSelectedIds', $this->dto['header']);

        return view('livewire..events.versions.reports.participating-students-component',
            [
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'summaryCounts' => $this->getSummaryCounts(),
            ]);
    }

    private function getRows(): Builder
    {
        $search = $this->search;

//        $this->troubleShooting($search, $this->versionId, $this->sortCol, $this->sortAsc);

        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users AS student', 'student.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->leftJoin('phone_numbers as mobile', function ($join) {
                $join->on('mobile.user_id', '=', 'teachers.user_id')
                    ->where('mobile.phone_type', '=',
                        'mobile'); // Assuming there's a type column to distinguish phone types
            })
            ->leftJoin('phone_numbers as work', function ($join) {
                $join->on('work.user_id', '=', 'teachers.user_id')
                    ->where('work.phone_type', '=',
                        'work'); // Assuming there's a type column to distinguish phone types
            })
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where(function ($query) use ($search) {
                return $query->where('schools.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('teacher.last_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('student.last_name', 'LIKE', '%'.$search.'%');
            })
            ->tap(function ($query) {
                $this->filters->filterCandidatesByParticipatingSchools($query);
                $this->filters->filterCandidatesByParticipatingClassOfs($query);
                $this->filters->filterCandidatesByParticipatingVoiceParts($query);
            })
            ->select('candidates.id', 'candidates.voice_part_id',
                'schools.name as schoolName',
                DB::raw("CONCAT(teacher.last_name, ', ', teacher.first_name, ' ', teacher.middle_name) AS teacherFullName"),
                'teacher.prefix_name', 'teacher.first_name', 'teacher.middle_name', 'teacher.last_name',
                'teacher.suffix_name', 'teacher.email',
                'student.first_name AS studentFirstName', 'student.middle_name AS studentMiddleName',
                'student.last_name AS studentLastName', 'student.suffix_name AS studentSuffix',
                'voice_parts.descr AS voicePartDescr', 'voice_parts.order_by',
                'students.class_of',
                DB::raw("((12 - (students.class_of - 2025))) AS grade"),
                'mobile.phone_number AS phoneMobile',
                'work.phone_number AS phoneWork'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('schools.name')
            ->orderBy('studentLastName')
            ->orderBy('studentFirstName')
            ->orderBy('voice_parts.order_by');
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new ParticipatingStudentsExport(
            $this->getRows()->get()->toArray(),
        ), 'participatingStudents.csv');
    }

    private function makeFullName(): string
    {
        $str = trim($this->form->first).' ';
        if (strlen($this->form->middle)) {
            $str .= trim($this->form->middle).' ';
        }
        $str .= trim($this->form->last);

        return $str;
    }
}
