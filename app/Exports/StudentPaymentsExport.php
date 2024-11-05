<?php

namespace App\Exports;

use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\CoTeachersService;
use App\Services\StudentPaymentsService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentPaymentsExport implements FromArray, WithHeadings
{
    /**
     * @return array
     */
    public function array(): array
    {
        $versionId = UserConfig::getValue('versionId');
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schoolIds = $teacher->schools()->pluck('schools.id')->toArray();
        $coTeacherIds = CoTeachersService::getCoTeachersIds();

        $service = new StudentPaymentsService($schoolIds, $coTeacherIds, $versionId);

        return $service->getPaymentsExport();

    }

    public function headings(): array
    {
        return [
            'first', 'middle', 'last', 'amount', 'transactionId', 'payment type', 'comments'
        ];
    }
}
