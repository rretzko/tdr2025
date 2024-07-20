<?php

namespace App\Livewire\Events\Versions;

use App\Exports\VersionRolesExport;
use App\Livewire\BasePage;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use App\Models\Schools\Teacher;
use App\Models\User;
use App\ValueObjects\TeacherNameAndSchoolValueObject;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class VersionRoleComponent extends BasePage
{
    public const ROLES = [
        'co-registration manager',
        'event manager',
        'on-site registrar',
        'online registration manager',
        'registration manager',
        'tab room',
    ];
    public int $versionId = 0;
    public int $showAddRoleForm = 0;
    public int $showEditRoleForm = 0;
    public string $showEditRoleFormName = '';
    public string $showEditRoleFormRole = '';
    public int $searchParticipantId = 0;
    public string $searchRole = '';

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['id'];

        $this->sortCol = 'users.last_name';

    }

    public function render()
    {
        return view('livewire.events.versions.version-role-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'participants' => $this->getParticipants(),
                'roles' => self::ROLES,
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]
        );
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'], //users.last_name
            ['label' => 'school', 'sortBy' => 'school'], //users.last_name
            ['label' => 'role', 'sortBy' => 'role'],
        ];
    }

    private function getParticipants(): array
    {
        $vps = VersionParticipant::query()
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->join('teachers', 'teachers.user_id', '=', 'users.id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->where('version_participants.version_id', $this->versionId)
            ->select('version_participants.id',
                'users.id as userId', 'users.last_name', 'users.first_name', 'users.middle_name',
                'schools.name as schoolName')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();

        $a = [];
        foreach ($vps as $teacher) {

            $a[$teacher['id']] = $teacher['last_name'].', '.$teacher['first_name'].' '.$teacher['middle_name'].' ('.$teacher['schoolName'].')';
        }

        return $a;
    }

    private function getRows(): Builder
    {
//        $this->test();
        return VersionRole::query()
            ->join('version_participants', 'version_participants.id', '=', 'version_roles.version_participant_id')
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->join('teachers', 'teachers.user_id', '=', 'users.id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->where('version_roles.version_id', $this->versionId)
            ->where('users.name', 'LIKE', '%'.$this->search.'%')
            ->orWhere('schools.name', 'LIKE', '%'.$this->search.'%')
            ->select('version_roles.id', 'version_roles.role',
                'version_participants.id as versionParticipantsId', 'version_participants.status',
                'users.id as userId', 'users.last_name', 'users.first_name', 'users.middle_name',
                'schools.name as schoolName')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new VersionRolesExport, 'roles.csv');
    }

    public function participantRoleUpdate(): void
    {
        VersionRole::find($this->showEditRoleForm)
            ->update(['role' => $this->showEditRoleFormRole]);

        $this->reset('showEditRoleFormRole', 'showEditRoleFormName',
            'showEditRoleForm');

    }

    public function remove(int $versionRoleId): void
    {
        VersionRole::find($versionRoleId)->delete();

        $this->showSuccessIndicator = true;
        $this->successMessage = 'Role removed.';
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'users.last_name',
            'school' => 'schools.name',
            'role' => 'version_roles.role',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];
    }

    public function updateRole(): void
    {
        if ($this->showAddRoleForm) {

            VersionRole::create(
                [
                    'version_id' => $this->versionId,
                    'version_participant_id' => $this->searchParticipantId,
                    'role' => $this->searchRole,
                ]
            );
        } else {

            VersionRole::updateOrCreate(
                [
                    'version_id' => $this->versionId,
                    'version_participant_id' => $this->searchParticipantId,
                ],
                [
                    'role' => $this->searchRole,
                ]
            );
        }

        $this->showSuccessIndicator = true;
        $this->successMessage = 'Roles updated.';
    }

    public function updatedShowEditRoleForm(): void
    {
        $vr = VersionRole::find($this->showEditRoleForm);
        $vp = VersionParticipant::find($vr->version_participant_id);
        $user = User::find($vp->user_id);
        $teacher = Teacher::where('user_id', $user->id)->first();
        $this->showEditRoleFormName = TeacherNameAndSchoolValueObject::getVo($teacher);
        $this->showEditRoleFormRole = $vr->role;
    }

    private function test(): void
    {
        dd(VersionRole::query()
            ->join('version_participants', 'version_participants.id', '=', 'version_roles.version_participant_id')
//        ->join('users', 'users.id', '=', 'version_participants.user_id')
//        ->join('teachers', 'teachers.user_id', '=', 'users.id')
//        ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
//        ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
//        ->where('version_roles.version_id', $this->versionId)
//        ->where('users.name', 'LIKE', '%'.$this->search.'%')
//        ->orWhere('schools.name', 'LIKE', '%'.$this->search.'%')
//        ->select('version_roles.role',
//            'version_participants.id', 'version_participants.status',
//            'users.id as userId', 'users.last_name', 'users.first_name', 'users.middle_name',
//            'schools.name as schoolName')
//        ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
//        ->orderBy('users.last_name')
//        ->orderBy('users.first_name')
            ->get());
    }

}
