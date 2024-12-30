<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Exports\ObligatedTeachersExport;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigMembership;
use App\Models\UserConfig;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ObligatedTeachersComponent extends BasePageReports
{
    public array $columnHeaders = [];
    public bool $membershipCardRequired = false;
    public Version $version;

    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = false;

        $this->columnHeaders = $this->getColumnHeaders();
        $this->version = Version::find($this->versionId);

        $this->membershipCardRequired = VersionConfigMembership::where('version_id', $this->versionId)
            ->first()
            ->membership_card;

        //sorts
        $this->sortCol = $this->userSort ? $this->userSort->column : 'users.last_name';
        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'name';

    }

    private function getColumnHeaders(): array
    {
        $headers = [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'], //users.last_name
            ['label' => 'school', 'sortBy' => 'school'],
        ];

        if ($this->membershipCardRequired) {
            $headers[] = ['label' => 'expiration', 'sortBy' => null];
        }

        return $headers;
    }

    public function render()
    {
        $this->saveSortParameters();

        return view('livewire..events.versions.reports.obligated-teachers-component',
            [
                'rows' => $this->getRows()->paginate($this->recordsPerPage), //obligatedTeachers(),
            ]);
    }

    private function getRows(): Builder
    {
        $search = $this->search;

        return DB::table('obligations')
            ->join('users', 'users.id', '=', 'obligations.teacher_id')
            ->join('teachers', 'teachers.id', '=', 'obligations.teacher_id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'obligations.teacher_id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->join('school_grades', 'school_grades.school_id', '=', 'schools.id')
            ->join('version_config_memberships', 'obligations.version_id', '=', 'version_config_memberships.version_id')
            ->leftJoin('phone_numbers as mobile', function ($join) {
                $join->on('mobile.user_id', '=', 'users.id')
                    ->where('mobile.phone_type', '=',
                        'mobile'); // Assuming there's a type column to distinguish phone types
            })
            ->leftJoin('phone_numbers as work', function ($join) {
                $join->on('work.user_id', '=', 'users.id')
                    ->where('work.phone_type', '=',
                        'work'); // Assuming there's a type column to distinguish phone types
            })
            ->where('obligations.version_id', $this->versionId)
            ->where('school_teacher.active', 1)
            ->where(function ($query) use ($search) {
                return $query
                    ->where('users.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('schools.name', 'LIKE', '%'.$search.'%');
            })
            ->select('obligations.accepted',
                'users.prefix_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix_name',
                'users.name',
                'schools.name AS schoolName',
                DB::raw('GROUP_CONCAT(school_grades.grade ORDER BY school_grades.grade ASC SEPARATOR ", ") AS grades'),
                'users.email',
                'mobile.phone_number AS phoneMobile',
                'work.phone_number AS phoneWork',
                'version_config_memberships.valid_thru',
            )
            ->groupBy(
                'obligations.accepted',
                'users.prefix_name',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.suffix_name',
                'schools.name',
                'version_config_memberships.valid_thru',
                'users.email',
                'phoneMobile',
                'phoneWork',
                'users.name',
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->orderBy('schools.name');
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new ObligatedTeachersExport(
            $this->versionId,
            $this->membershipCardRequired,
            $this->getRows()->get()->toArray(),
        ), 'obligatedTeachers.csv');
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'users.last_name',
            'school' => 'schools.name',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }

    public function total(): int
    {
        return $this->getRows()->count();
    }
}
