<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Exports\StudentPaymentsExport;
use App\Livewire\BasePage;
use App\Livewire\Forms\StudentPaymentForm;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use App\Services\ConvertToUsdService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EstimateComponent extends BasePage
{
    public StudentPaymentForm $form;
    public bool $addNewPayment = false;
    public array $columnHeaders = [];
    public array $paymentTypes = ['cash' => 'cash', 'check' => 'check'];
    public float $registrationFee = 0.00;
    public string $selectedTab = 'payments';//estimate
    public int $showEditForm = 0;
    public array $studentPaymentColumnHeaders = [];
    public array $tabs = [];
    public int $versionId = 0;

    public bool $showSuccessIndicatorPaymentTypeAmount = false;
    public string $successMessagePaymentTypeAmount = '';

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->registrationFee = $this->getRegistrationFee();
        $this->sortColLabel = 'users.name';
        $this->studentPaymentColumnHeaders = $this->getStudentPaymentColumnHeaders();
        $this->tabs = ['estimate', 'payments', 'payPal'];
        $this->versionId = $this->dto['id'];
    }

    public function render()
    {
        return view('livewire..events.versions.participations.estimate-component',
            [
                'candidates' => $this->getCandidates(),
                'registrants' => $this->getRegistrantArrayForEstimateForm(),
                'studentPayments' => $this->getStudentPayments(),
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new StudentPaymentsExport, 'studentPayments.csv');
    }

    public function remove(int $studentPaymentId): void
    {
        dd('studentPaymentId: '.$studentPaymentId);
    }

    public function updatedForm($value, $key): void
    {
        if ($this->form->updateProperty($value, $key)) {

            $this->showSuccessIndicator = true;
            $this->successMessage = Str::headline($key).' has been updated.';
        }
    }

    public function updatedShowEditForm(): void
    {
        $this->form->setStudentPaymentProperties($this->showEditForm);
    }

    private function getCandidates(): array
    {
        $statuses = ['eligible', 'engaged', 'no-app', 'pre-registered', 'registered'];

        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('school_id', $this->school->id)
            ->where('version_id', $this->versionId)
            ->whereIn('status', $statuses)
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->pluck('users.name', 'candidates.id')
            ->toArray();
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'],
            ['label' => 'voice part', 'sortBy' => 'voicePartDescr'],
            ['label' => 'grade', 'sortBy' => 'grade'],
            ['label' => 'fee', 'sortBy' => null],
        ];
    }

    private function getRegistrationFee(): float
    {
        $fee = Version::find(UserConfig::getValue('versionId'))->fee_registration;

        return ConvertToUsdService::penniesToUsd($fee);
    }

    private function getRegistrantArrayForEstimateForm(): array
    {
        $registrant = new Registrant(
            UserConfig::getValue('schoolId'),
            UserConfig::getValue('versionId')
        );

        return $registrant->getRegistrantArrayForEstimateForm();
    }

    private function getStudentPaymentColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => null],
            ['label' => 'amount', 'sortBy' => null],
            ['label' => 'type', 'sortBy' => null],
            ['label' => 'transaction id', 'sortBy' => null],
            ['label' => 'comments', 'sortBy' => null],
        ];
    }

    private function getStudentPayments(): array
    {
        return StudentPayment::query()
            ->join('students', 'students.id', '=', 'student_payments.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('version_id', $this->versionId)
            ->where('school_id', $this->school->id)
            ->select('student_payments.id', 'student_payments.candidate_id',
                'student_payments.amount', 'student_payments.payment_type', 'student_payments.transaction_id',
                'student_payments.comments',
                'users.id AS userId', 'users.first_name', 'users.middle_name', 'users.last_name',
                'users.suffix_name')
            ->get()
            ->toArray();
    }
}
