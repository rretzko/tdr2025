<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Exports\StudentPaymentsExport;
use App\Livewire\BasePage;
use App\Livewire\Forms\StudentPaymentForm;
use App\Models\Epayment;
use App\Models\EpaymentCredentials;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\ConvertToUsdService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EstimateComponent extends BasePage
{
    public StudentPaymentForm $form;
    public bool $addNewPayment = false;
    public float $amountDue = 0;
    public array $columnHeaders = [];
    public string $customProperties = '';
    public string $email = 'realEpayment@email.com';
    public string $ePaymentId = 'ePaymentId';
    public string $ePaymentVendor = 'none';
    public array $paymentTypes = ['cash' => 'cash', 'check' => 'check'];
    public float $registrationFee = 0.00;
    public bool $sandbox = true;
    public string $sandboxId = 'sandboxId';
    public string $sandboxPersonalEmail = '';
    public string $selectedTab = 'ePayments'; //'estimate';
    public int $showEditForm = 0;
    public array $studentPaymentColumnHeaders = [];
    public array $tabs = [];
    public string $teacherName = '';
    public int $userId = 0;
    public int $versionId = 0;
    public string $versionShortName = '';

    public bool $showSuccessIndicatorPaymentTypeAmount = false;
    public string $successMessagePaymentTypeAmount = '';

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->columnHeaders = $this->getColumnHeaders();
        $this->registrationFee = $this->getRegistrationFee();
        $this->sortColLabel = 'users.name';
        $this->studentPaymentColumnHeaders = $this->getStudentPaymentColumnHeaders();
        $this->tabs = $this->getTabs();

        //ePayments
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $version = Version::find($this->versionId);

        $this->amountDue = $this->getAmountDue();
        $this->customProperties = $this->getCustomProperties();
        $this->email = auth()->user()->email;
        $this->ePaymentId = $this->getEpaymentId();
        $this->sandbox = true;
        $this->sandboxId = 'sb-qw0iu20847075@business.example.com';
        $this->sandboxPersonalEmail = 'sb-ndsz820837854@personal.example.com'; //dRkJ4(f)
        $this->teacherName = $teacher->user->name;
        $this->userId = auth()->id();
        $this->versionShortName = $version->short_name;
        $this->ePaymentVendor = $version->epayment_vendor;
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

    private function getAmountDue(): float
    {
        //how many registrants are there
        $registrant = new Registrant($this->school->id, $this->versionId);
        $registrantCount = $registrant->getRegistrantCount();

        //what is the registration fee
        $version = Version::find($this->versionId);
        $registrationFee = $version->fee_registration; //in pennies

        //what is the total expected payment
        $totalExpected = ($registrantCount * $registrationFee); //in pennies

        //how much has already been collected through ePayments
        $ePayment = new Epayment();
        $totalCollected = $ePayment->getTotalCollected($version, $this->school->id); //in pennies

        //return how much remains to be collected
        return ConvertToUsdService::penniesToUsd($totalExpected - $totalCollected);
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
            ['label' => 'payment', 'sortBy' => null],
            ['label' => 'fee', 'sortBy' => null],
        ];
    }

    /**
     * Provide PayPal form with custom properties for tracking payments from
     * the PayPal website
     * @return string
     */
    private function getCustomProperties(): string
    {
        $separator = ' | ';

        $properties = [
            (string) $this->userId,
            (string) $this->versionId,
            (string) $this->school->id,
            (string) $this->amountDue,
            '0', //expecting 'candidate_id' but using '0' to indicate teacher
            'registration', //fee type
            auth()->user()->name, //additional identification info
        ];

        return implode($separator, $properties);
    }

    private function getEpaymentId(): string
    {
        $ePaymentCredentials = EpaymentCredentials::query()
            ->where('version_id', $this->versionId)
            ->first();

        $version = Version::find($this->versionId);
        if (!$ePaymentCredentials) {

            $ePaymentCredentials = EpaymentCredentials::query()
                ->where('event_id', $version->event_id)
                ->first();
        }

        return ($ePaymentCredentials)
            ? $ePaymentCredentials->epayment_id
            : '';
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

    private function getTabs(): array
    {
        $tabs = ['estimate', 'payments'];
        $version = Version::find($this->versionId);

        if ($version && $version->epayment_teacher) {

            $tabs[] = 'ePayments';
        }

        return $tabs;
    }
}
