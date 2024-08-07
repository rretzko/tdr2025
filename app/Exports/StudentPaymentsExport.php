<?php

namespace App\Exports;

use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\UserConfig;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentPaymentsExport implements FromQuery, WithHeadings
{
    /**
     * @return
     */
    public function query()
    {
        $versionId = UserConfig::getValue('versionId');
        $schoolId = UserConfig::getValue('schoolId');

        return StudentPayment::query()
            ->join('students', 'students.id', '=', 'student_payments.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('version_id', $versionId)
            ->where('school_id', $schoolId)
            ->select('users.last_name', 'users.first_name', 'users.middle_name',
                'users.suffix_name',
                DB::raw('student_payments.amount / 100 as amount'),
                'student_payments.payment_type', 'student_payments.transaction_id',
                'student_payments.comments',)
            ->orderBY('users.last_name')
            ->orderBy('users.first_name');
    }

    public function headings(): array
    {
        return [
            'last', 'first', 'middle', 'suffix', 'amount', 'type',
            'transaction id', 'comments'
        ];
    }
}
