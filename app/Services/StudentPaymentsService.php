<?php

namespace App\Services;

use App\Models\Epayment;
use App\Models\Events\Versions\Participations\StudentPayment;
use Illuminate\Database\Eloquent\Builder;

class StudentPaymentsService
{
    private array $payments = [];

    public function __construct(
        private readonly array $schoolIds,
        private readonly array $coteacherIds,
        private int $versionId,
        private string $sortCol = 'users.last_name',
        private string $sortAsc = 'asc',
    ) {
        $this->init();
    }

    private function init(): void
    {
        $physicalPayments = $this->getPhysicalPayments();
        $ePayments = $this->getEpayments();

        foreach ($ePayments as $key => $payment) {
            $ePayments[$key]['payment_type'] = 'ePayment';
        }

        $this->payments = array_merge($physicalPayments, $ePayments);

        $this->sortArray();

    }

    private function getPhysicalPayments(): array
    {
        return StudentPayment::query()
            ->join('students', 'students.id', '=', 'student_payments.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('version_id', $this->versionId)
            ->whereIn('school_id', $this->schoolIds)
            ->select('student_payments.id', 'student_payments.candidate_id',
                'student_payments.amount', 'student_payments.payment_type', 'student_payments.transaction_id',
                'student_payments.comments',
                'users.id AS userId', 'users.first_name', 'users.middle_name', 'users.last_name',
                'users.suffix_name')
            ->get()
            ->toArray();
    }

    private function getEPayments(): array
    {
        return Epayment::query()
            ->join('students', 'students.id', '=', 'epayments.user_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('version_id', $this->versionId)
            ->whereIn('school_id', $this->schoolIds)
            ->select('epayments.id', 'epayments.candidate_id',
                'epayments.amount', 'epayments.transaction_id',
                'epayments.comments',
                'users.id AS userId', 'users.first_name', 'users.middle_name', 'users.last_name',
                'users.suffix_name')
            ->get()
            ->toArray();
    }

    private function sortArray()
    {
        usort($this->payments, function ($a, $b) {
            return strcmp($a['last_name'], $b['last_name']);
        });
    }

    public function getPayments(): array
    {
        return $this->payments;
    }

    public function getPaymentsExport(): array
    {
        return $this->getPaymentsExportArray();
    }

    private function getPaymentsExportArray(): array
    {
        $exports = [];

        foreach ($this->payments as $payment) {

            $exports[] = [
                'first' => $payment['first_name'],
                'middle' => $payment['middle_name'],
                'last' => $payment['last_name'],
                'amount' => ConvertToUsdService::penniesToUsd($payment['amount']),
                'transactionId' => $payment['transaction_id'],
                'type' => $payment['payment_type'],
                'comments' => $payment['comments'],
            ];
        }

        return $exports;
    }

}
