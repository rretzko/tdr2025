<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Exports\StudentPaymentsExport;
use App\Livewire\BasePage;
use App\Livewire\Forms\StudentPaymentForm;
use App\Models\Address;
use App\Models\Epayment;
use App\Models\EpaymentCredentials;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\ConvertToUsdService;
use App\Services\CoTeachersService;
use App\Services\StudentPaymentsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EstimateComponent extends BasePage
{
    public StudentPaymentForm $form;
    public bool $addNewPayment = false;
    public float $amountDue = 0;
    public array $columnHeaders = [];
    public string|bool $coregistrationManagerAddress = false;
    public array $coregistrationManagerAddressArray = [];
    public string $customProperties = '';
    public string $email = 'realEpayment@email.com';
    public string $ePaymentId = 'ePaymentId';
    public string $ePaymentVendor = 'none';
    public array $paymentTypes = ['cash' => 'cash', 'check' => 'check'];
    public float $registrationFee = 0.00;
    public bool $sandbox = false;
    public string $sandboxId = 'sandboxId';
    public string $sandboxPersonalEmail = '';
    public string $schoolName = '';
    public string $selectedTab = 'estimate';
    public int $showEditForm = 0;
    public array $studentPaymentColumnHeaders = [];
    public array $tabs = [];
    public string $teacherName = '';
    public int $userId = 0;
    public int $versionId = 0;
    public string $versionShortName = '';

    public bool $showSuccessIndicatorPaymentTypeAmount = false;
    public string $successMessagePaymentTypeAmount = '';

    //square variables
    public string $firstName = '';
    public string $lastName = '';
    public string $phone = '';
    public string $city = '';
    public string $dataUrl = '';
    public string $href = '';
    public string $geostateAbbr = 'NJ';

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->columnHeaders = $this->getColumnHeaders();
        $this->registrationFee = $this->getRegistrationFee();
        $this->schoolName = School::find(UserConfig::getValue('schoolId'))->name;
        $this->sortCol = 'users.last_name';
        $this->sortColLabel = 'users.name';
        $this->studentPaymentColumnHeaders = $this->getStudentPaymentColumnHeaders();
        $this->tabs = $this->getTabs();
        $this->coregistrationManagerAddress = $this->getCoregistrationManagerAddress();
        $this->coregistrationManagerAddressArray = explode(',', $this->coregistrationManagerAddress);

        //ePayments
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $version = Version::find($this->versionId);

        //paypal
        $this->amountDue = $this->getAmountDue();
        $this->customProperties = $this->getCustomProperties();
        $this->email = auth()->user()->email;
        $this->ePaymentId = $this->getEpaymentId();
        $this->sandbox = false;
        $this->sandboxId = 'sb-qw0iu20847075@business.example.com';
        $this->sandboxPersonalEmail = 'sb-ndsz820837854@personal.example.com'; //dRkJ4(f)
        $this->teacherName = $teacher->user->name;
        $this->userId = auth()->id();
        $this->versionShortName = $version->short_name;
        $this->ePaymentVendor = $version->epayment_vendor;

        //square
        $user = auth()->user();
        $address = Address::where('user_id', auth()->id())->first();
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->phone = $user->phoneNumbers()->where('phone_type', 'mobile')->first() ?? '';
        $this->city = $address->city ?? '';
        $this->geostateAbbr = $address->geostateAbbr ?? 'NJ';
        $version = Version::find($this->versionId);
        $squareEpaymentCredential = EpaymentCredentials::where('event_id', $version->event->id)->first();
        /**
         * @todo replace hard-coded values below with routine to find the epayment_id linked to directors
         *  - add a boolean student or director value with respective code looking for a version_id and student/director = 0 or 1
         */
        $this->dataUrl = 'https://square.link/u/sbc7pIXd?src=embed'; //$squareEpaymentCredential->epayment_id; //ex. https://square.link/u/12345678?src=embed
        $this->href = 'https://square.link/u/sbc7pIXd?src=embed';
        $squareEpaymentCredential->epayment_id; //ex. https://square.link/u/12345678?src=embed

    }

    public function render()
    {
        $this->saveSortParameters();

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

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'users.last_name',
            'grade' => 'students.class_of',
            'voicePartDescr' => 'voice_parts.order_by',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

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
        $registrant = ($this->school->id)
            ? new Registrant($this->school->id, $this->versionId)
            : null;

        $registrantCount = (!is_null($registrant))
            ? $registrant->getRegistrantCount()
            : $this->getMultipleSchoolRegistrantCount();

        //what is the registration fee
        $version = Version::find($this->versionId);
        $registrationFee = $version->fee_registration; //in pennies

        //what is the total expected payment
        $totalExpected = ($registrantCount * $registrationFee); //in pennies

        //how much has already been collected through ePayments
        $ePayment = new Epayment();
        $totalCollected = ($this->school->id)
            ? $ePayment->getTotalCollected($version, $this->school->id) //in pennies
            : $this->getMultipleSchoolTotalCollected($ePayment, $version);

        //return how much remains to be collected
        return ConvertToUsdService::penniesToUsd($totalExpected - $totalCollected);
    }

    private function getCandidates(): array
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();
        $schoolIds = (!is_null($this->school->id))
            ? [$this->school->id]
            : $this->getMultipleSchoolIds();

        $statuses = ['eligible', 'engaged', 'no-app', 'pre-registered', 'registered'];

        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('school_student', 'school_student.student_id', '=', 'candidates.student_id')
            ->join('student_teacher', 'student_teacher.student_id', '=', 'candidates.student_id')
            ->whereIn('candidates.school_id', $schoolIds)
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.status', $statuses)
            ->whereIn('school_student.school_id', $schoolIds)
            ->whereIn('student_teacher.teacher_id', $coTeacherIds)
            ->where('school_student.active', 1)
            ->where('candidates.teacher_id', auth()->id())
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

    private function getCoregistrationManagerAddress(): string|bool
    {
        $version = Version::find($this->versionId);

        if (!$version->hasCoregistrationManager()) {
            return false;
        }

        $schoolId = UserConfig::getValue('schoolId');

        return $version->getCoregistrationManagerAddressBySchoolCounty($schoolId);
//        return 'Carol Beadle, Ridge High School, South Finley Avenue, Basking Ridge, NJ, 07920';
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

    private function getMultipleSchoolIds(): array
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        return $teacher->schools()->pluck('schools.id')->toArray();
    }

    private function getMultipleSchoolRegistrantArrayForEstimateForm(): array
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schools = $teacher->schools;

        $registrants = [];

        foreach ($schools as $school) {

            $registrant = new Registrant($school->id, $this->versionId);
            $registrants = array_merge($registrants, $registrant->getRegistrantArrayForEstimateForm());

        }

        usort($registrants, function ($a, $b) {
            return strcmp($a->last_name, $b->last_name);
        });

        return $registrants;
    }

    /**
     * used for calculating total registrants when auth()->id() co-teaches with others
     * @return int
     */
    private function getMultipleSchoolRegistrantCount(): int
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schools = $teacher->schools;
        $count = 0;

        foreach ($schools as $school) {

            $registrant = new Registrant($school->id, $this->versionId);
            $count += $registrant->getRegistrantCount();
        }

        return $count;
    }

    /**
     * used for calculating total fees collected when auth()->id() co-teaches with others
     * @return int
     */
    private function getMultipleSchoolTotalCollected(Epayment $ePayment, Version $version): int
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schools = $teacher->schools;
        $total = 0;

        foreach ($schools as $school) {

            $total += $ePayment->getTotalCollected($version, $school->id); //in pennies
        }

        return $total;
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

        $registrants = ($this->school->id)
            ? $registrant->getRegistrantArrayForEstimateForm()
            : $this->getMultipleSchoolRegistrantArrayForEstimateForm();

        return $this->sortRegistrants($registrants);
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
        $schoolIds = (!is_null($this->school->id))
            ? [$this->school->id]
            : $this->getMultipleSchoolIds();

        $coteacherIds = CoTeachersService::getCoTeachersIds();

        $service = new StudentPaymentsService(
            $schoolIds,
            $coteacherIds,
            $this->versionId,
            $this->sortCol,
            $this->sortAsc
        );

        return $service->getPayments();
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

    /**
     * @param  array  $registrants
     * 0 => {#2802 ▼
     * +"id": 826237
     * +"first_name": "Michael"
     * +"middle_name": ""
     * +"last_name": "Vespignani"
     * +"suffix_name": null
     * +"class_of": 2026
     * +"voicePartDescr": "Bass II"
     * +"grade": 11
     * +"payment": 20.0
     * }
     * @return array
     */
    private function sortRegistrants(array $registrants): array
    {
        if (!$this->sortCol) {
            $this->sortCol = 'users.last_name';
        }

        $sortCol = match ($this->sortCol) {
            'students.class_of' => 'grade',
            'users.last_name' => 'last_name',
            'voice_parts.order_by' => 'voicePartDescr',
        };

        usort($registrants, function ($a, $b) use ($sortCol) {
            return strcmp($a->$sortCol, $b->$sortCol);
        });

        return $registrants;
    }
}
