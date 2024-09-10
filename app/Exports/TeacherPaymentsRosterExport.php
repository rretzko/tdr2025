<?php

namespace App\Exports;

use App\Models\Events\Versions\TeacherPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeacherPaymentsRosterExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly int $versionId)
    {

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $payments = TeacherPayment::query()
            ->join('schools', 'schools.id', '=', 'teacher_payments.school_id')
            ->join('users', 'users.id', '=', 'teacher_payments.user_id')
            ->where('teacher_payments.version_id', $this->versionId)
            ->where('teacher_payments.amount', '<>', 0)
            ->select('schools.name AS schoolName',
                'teacher_payments.fee_type', 'teacher_payments.payment_type', 'teacher_payments.amount',
                'teacher_payments.transaction_id', 'teacher_payments.comments',
                'users.name',
                'teacher_payments.updated_at')
            ->get();

        // Format the updated_at field
        $payments->each(function ($payment) {
            $payment->updated_at = \Carbon\Carbon::parse($payment->updated_at)->format('F d, Y h:i A');
        });

        return $payments;
    }

    public function headings(): array
    {
        return [
            'school',
            'fee type',
            'payment_type',
            'amount',
            'id',
            'comments',
            'added by',
            'last changed'
        ];
    }
}
