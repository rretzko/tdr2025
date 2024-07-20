<?php

namespace App\Livewire\Events\Versions;

use App\Exports\VersionParticipantsExport;
use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Schools\Teacher;
use App\Models\User;
use App\Services\UserNameService;
use App\ValueObjects\TeacherNameAndSchoolValueObject;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class VersionParticipantsTableComponent extends BasePage
{
    public int $versionId;
    public string $searchEmail = '';
    public string $searchFound = '';
    public int $searchUserId = 0;
    public bool $showAddParticipantForm = false;
    public int $showEditParticipantForm = 0;
    public string $showEditParticipantFormName = '';
    public string $showEditParticipantFormStatus = '';

    public function mount(): void
    {
        parent::mount();

        $this->hasSearch = true;

        $this->versionId = $this->dto['id'];

        $this->sortCol = 'users.last_name';
    }

    public function render()
    {
        return view('livewire..events.versions.version-participants-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'teachers' => $this->getTeachersArray(),
                'statuses' => ['invited', 'obligated', 'participating', 'prohibited', 'withdrew'],
            ]);
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'], //users.last_name
            ['label' => 'school', 'sortBy' => 'school'], //users.last_name
            ['label' => 'status', 'sortBy' => 'status'],
        ];
    }

    private function getRows(): Builder
    {
        return VersionParticipant::query()
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->join('teachers', 'teachers.user_id', '=', 'users.id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->where('version_id', $this->dto['id'])
            ->where('users.name', 'LIKE', '%'.$this->search.'%')
            ->orWhere('schools.name', 'LIKE', '%'.$this->search.'%')
            ->select('version_participants.id', 'version_participants.status',
                'users.id as userId', 'users.last_name', 'users.first_name', 'users.middle_name',
                'schools.name as schoolName')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');
    }

    private function getTeachersArray(): array
    {
        $participantUserIds = VersionParticipant::query()
            ->where('version_id', $this->versionId)
            ->pluck('user_id')
            ->toArray();

        $teachers = Teacher::query()
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->whereNotIn('teachers.user_id', $participantUserIds)
            ->select('users.id as userId', 'users.last_name', 'users.first_name', 'users.middle_name',
                'schools.name as schoolName')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get();

        $a = [];
        foreach ($teachers as $teacher) {

            $a[$teacher['userId']] = $teacher['last_name'].', '.$teacher['first_name'].' '.$teacher['middle_name'].' ('.$teacher['schoolName'].')';
        }

        return $a;
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new VersionParticipantsExport, 'participants.csv');
    }

    public function inviteTeacher(): void
    {
        VersionParticipant::updateOrCreate(
            [
                'version_id' => $this->versionId,
                'user_id' => $this->searchUserId,
            ],
            [
                'status' => 'invited',
            ]
        );

        $this->showSuccessIndicator = true;
        $this->successMessage = 'Teacher has been added to the roster.';
        $this->reset('searchUserId', 'searchFound', 'showAddParticipantForm', 'searchEmail');
    }

    public function participantStatusUpdate(): void
    {
        VersionParticipant::find($this->showEditParticipantForm)
            ->update(['status' => $this->showEditParticipantFormStatus]);

        $this->reset('showEditParticipantFormStatus', 'showEditParticipantFormName',
            'showEditParticipantForm');
    }

    public function remove(int $versionParticipantId)
    {
        $vp = VersionParticipant::find($versionParticipantId);

        $vp->delete();

        $this->showSuccessIndicator = true;
        $this->successMessage = 'The participant has been removed.';
    }

    public function searchForParticipant(): void
    {
        //prioritize selected name over email
        if ($this->searchUserId) {

            $teacher = Teacher::where('user_id', $this->searchUserId)->first();

        } elseif ( //ensure that if search for email is found, user is also a teacher
            ($this->searchEmail) &&
            User::where('email', $this->searchEmail)->exists() &&
            Teacher::where('user_id', User::where('email', $this->searchEmail)->exists())
        ) {
            $teacher = Teacher::where('user_id', User::where('email', $this->searchEmail)->first()->id)->first();

            $this->searchUserId = $teacher->user_id;
        } else {

            $teacher = new Teacher();
        }

        $this->searchFound = ($teacher->id)
            ? TeacherNameAndSchoolValueObject::getVo($teacher)
            : '';

    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'users.last_name',
            'school' => 'schools.name',
            'status' => 'version_participants.status',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }

    public function updatedShowEditParticipantForm(): void
    {
        $vp = VersionParticipant::find($this->showEditParticipantForm);
        $user = User::find($vp->user_id);
        $teacher = Teacher::where('user_id', $user->id)->first();
        $this->showEditParticipantFormName = TeacherNameAndSchoolValueObject::getVo($teacher);
        $this->showEditParticipantFormStatus = $vp->status;
    }
}
