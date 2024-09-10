<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\TeacherPayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Services\ConvertToPenniesService;
use App\Services\ConvertToUsdService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TeacherPaymentForm extends Form
{
    public string $comments = '';
    public string $paidBy = 'check';
    public string $schoolName = '';
    public string $transactionId = '';
    public float $usdAmountDue = 0.00;
    #[Validate('required', 'min:.01')]
    public float $usdPayment = 0.00;

    #[Validate('required', 'int', 'exists:schools,id')]
    public int $schoolId = 0;
    #[Validate('required', 'int', 'exists:versions,id')]
    public int $versionId = 0;

    public function save()
    {
        $this->validate();

        return TeacherPayment::create(
            [
                'version_id' => $this->versionId,
                'school_id' => $this->schoolId,
                'user_id' => auth()->id(),
                'fee_type' => 'registration',
                'payment_type' => $this->paidBy,
                'transaction_id' => $this->transactionId,
                'amount' => ConvertToPenniesService::usdToPennies($this->usdPayment),
                'comments' => $this->comments,
            ]
        );
    }

    public function resetValues(): void
    {
        $this->reset('fee_type', 'usdPayment', 'transactionId', 'comments');
    }

    public function setSchool(int $schoolId, int $versionId): void
    {
        $this->versionId = $versionId;
        $this->schoolId = $schoolId;
        $this->schoolName = School::find($this->schoolId)->name;
        $paymentDue = $this->getPaymentDue();
        $paymentsPaid = $this->getPaymentsPaid();
        $this->usdAmountDue = ConvertToUsdService::penniesToUsd($paymentDue - $paymentsPaid);

    }

    private function getPaymentDue(): int
    {
        $feeRegistration = Version::find($this->versionId)->fee_registration;

        $candidateCount = Candidate::query()
            ->where('school_id', $this->schoolId)
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->count('id');

        return ($candidateCount * $feeRegistration);
    }

    private function getPaymentsPaid(): int
    {
        return DB::table('epayments')
            ->selectRaw('SUM(amount) as total')
            ->where('school_id', $this->schoolId)
            ->where('version_id', $this->versionId)
            ->where('fee_type', 'registration')
            ->unionAll(
                DB::table('teacher_payments')
                    ->selectRaw('SUM(amount) as total')
                    ->where('school_id', $this->schoolId)
                    ->where('version_id', $this->versionId)
                    ->where('fee_type', 'registration')
            )
            ->sum('total');
    }
}
