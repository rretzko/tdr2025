<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\Students\Student;
use App\Services\ConvertToPenniesService;
use App\Services\ConvertToUsdService;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentPaymentForm extends Form
{
    public float $amount = 0.00;
    public int $candidateId = 0;
    public string $comments = '';
    public string $paymentType = 'cash';
    public int $schoolId = 0;
    public string $studentFullName = '';
    public int $studentId = 0;
    public StudentPayment $studentPayment;
    public string $sysId = 'new';
    public string $transactionId = '';
    public int $versionId = 0;

    public function setStudentPaymentProperties(int $id): void
    {
        if ($id) {
            $this->studentPayment = StudentPayment::find($id);
            $this->amount = ConvertToUsdService::penniesToUsd($this->studentPayment->amount);
            $this->candidateId = $this->studentPayment->candidate_id;
            $this->comments = $this->studentPayment->comments;
            $this->paymentType = $this->studentPayment->payment_type;
            $this->schoolId = $this->studentPayment->school_id;
            $this->studentId = $this->studentPayment->student_id;
            $this->sysId = $this->studentPayment->id;
            $this->transactionId = $this->studentPayment->transaction_id;

            $this->studentFullName = Student::find($this->studentPayment->student_id)->user->name;
        } else {

            $this->studentPayment = new StudentPayment();
            $this->amount = 0.00;
            $this->candidateId = 0;
            $this->comments = '';
            $this->paymentType = 'cash';
            $this->schoolId = 0;
            $this->studentId = 0;
            $this->sysId = 'new';
            $this->transactionId = '';

            $this->studentFullName = '';

        }
    }

    public function updateProperty($value, $key): bool
    {
        if ($key === 'amount') {

            $value = ConvertToPenniesService::usdToPennies($value);
        }

        if ($this->sysId === 'new') {

            $this->createDefaultObject($value, $key);

            return true;
        }

        return $this->studentPayment->update([Str::lower(Str::snake($key)) => $value]);
    }

    private function createDefaultObject($value, $key): void
    {
        $candidate = Candidate::find($value);

        $this->candidateId = $value;

        $this->studentPayment = StudentPayment::create(
            [
                'student_id' => $candidate->student_id,
                'version_id' => $candidate->version_id,
                'school_id' => $candidate->school_id,
                'candidate_id' => $candidate->id,
                'amount' => 0,
                'transaction_id' => '',
                'comments' => '',
                'payment_type' => 'cash',
            ]
        );

        $this->sysId = $this->studentPayment->id;
    }
}
