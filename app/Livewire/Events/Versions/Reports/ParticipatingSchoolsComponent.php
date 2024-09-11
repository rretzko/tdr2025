<?php

namespace App\Livewire\Events\Versions\Reports;


use App\Exports\ParticipatingSchoolsExport;
use App\Exports\TeacherPaymentsRosterExport;
use App\Livewire\Forms\TeacherPaymentForm;
use App\Models\Epayment;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\TeacherPayment;
use App\Models\Events\Versions\Version;
use App\Services\ConvertToUsdService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ParticipatingSchoolsComponent extends BasePageReports
{
    public TeacherPaymentForm $form;
    public array $columnHeaders = [];
    public array $schoolIds = [];
    public bool $showPaymentForm = false;

    public function mount(): void
    {
        parent::mount();

        //sorts
        $this->sortCol = $this->userSort ? $this->userSort->column : 'schools.name';
        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'school';

        $this->columnHeaders = $this->getColumnHeaders();
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'name'], //users.last_name
            ['label' => 'registrant#', 'sortBy' => 'count'],
            ['label' => 'due', 'sortBy' => null],
            ['label' => 'paid', 'sortBy' => null],
            ['label' => 'payment', 'sortBy' => null],
        ];
    }

    public function render()
    {

        //refresh schoolIds
        $this->schoolIds = $this->getSchoolIds();
        $payments = $this->getPayments();
        $paymentsDue = $this->getPaymentsDue();
        $paymentsStatus = $this->getPaymentsStatus($payments, $paymentsDue);

        $this->saveSortParameters();

        return view('livewire..events.versions.reports.participating-schools-component',
            [
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'payments' => $payments,
                'paymentsDue' => $paymentsDue,
                'paymentsStatus' => $paymentsStatus,

            ]);
    }

    private function getSchoolIds(): array
    {
        return Candidate::where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->distinct('school_id')
            ->orderBy('school_id')
            ->pluck('school_id')
            ->toArray();
    }

    private function getPayments(): array
    {
        $totalPayments = [];

        foreach ($this->schoolIds as $schoolId) {

            $totalAmount = DB::table('epayments')
                ->selectRaw('SUM(amount) as total')
                ->where('school_id', $schoolId)
                ->where('version_id', $this->versionId)
                ->where('fee_type', 'registration')
                ->unionAll(
                    DB::table('teacher_payments')
                        ->selectRaw('SUM(amount) as total')
                        ->where('school_id', $schoolId)
                        ->where('version_id', $this->versionId)
                        ->where('fee_type', 'registration')
                )
                ->sum('total');

            $totalPayments[$schoolId] = $totalAmount;
        }

        return array_map(function ($value) {
            return ConvertToUsdService::penniesToUsd($value);
        }, $totalPayments);
    }

    private function getPaymentsDue(): array
    {
        $dues = [];
        $feeRegistration = Version::find($this->versionId)->fee_registration;

        foreach ($this->schoolIds as $schoolId) {

            $candidateCount = Candidate::query()
                ->where('school_id', $schoolId)
                ->where('version_id', $this->versionId)
                ->where('status', 'registered')
                ->count('id');

            $dues[$schoolId] = ($candidateCount * $feeRegistration);
        }

        return array_map(function ($value) {
            return ConvertToUsdService::penniesToUsd($value);
        }, $dues);
    }

    private function getPaymentsStatus(array $payments, array $paymentsDue): array
    {
        $paymentStatuses = [];

        foreach ($this->schoolIds as $schoolId) {

            $balance = ($paymentsDue[$schoolId] - $payments[$schoolId]);

            $paymentStatuses[$schoolId] = match (true) {
                (!$balance) => 'paid',
                ($balance > 0) => 'due',
                ($balance < 0) => 'refund',
                'default' => 'error',
            };
        }

        return $paymentStatuses;
    }

    private function getRows(): Builder
    {
        $search = $this->search;

        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->where(function ($query) use ($search) {
                return $query
                    ->where('users.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('schools.name', 'LIKE', '%'.$search.'%');
            })
            ->distinct(['candidates.school_id', 'candidates.teacher_id'])
            ->select('schools.name AS schoolName', 'schools.id AS schoolId',
                'users.prefix_name', 'users.last_name', 'users.middle_name', 'users.first_name', 'users.suffix_name',
                'users.name',
                DB::raw('COUNT(candidates.id) AS candidateCount'))
            ->groupBy(
                'candidates.school_id',
                'candidates.teacher_id',
                'schools.name',
                'users.prefix_name',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.suffix_name',
                'users.name',
                'schoolId'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');
    }

    public function createPayment(int $schoolId): void
    {
        $this->form->setSchool($schoolId, $this->versionId);

        $this->showPaymentForm = !$this->showPaymentForm;
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new ParticipatingSchoolsExport(
            $this->getPayments(),
            $this->getPaymentsDue(),
            $this->versionId,
            $this->schoolIds,
        ), 'participatingSchools.csv');
    }

    public function exportPaymentsRoster(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new TeacherPaymentsRosterExport(
            $this->versionId,
        ), 'paymentsRoster.csv');
    }

    public function save(): void
    {
        if ($this->form->save()) {
            $this->form->resetValues();
            $this->showSuccessIndicator = true;
            $this->successMessage = 'Payment saved';
        }
    }
}
