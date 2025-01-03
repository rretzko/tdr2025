<?php

namespace App\Exports;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Services\ConvertToUsdService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Laravel\Vapor\Arr;
use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipatingSchoolsExport implements FromArray, WithHeadings
{
    private array $baseArray = [];

    public function __construct(
        private readonly array $payments,
        private readonly array $paymentsDue,
        private readonly array $paymentStatus,
        private readonly array $schools,
    ) {
    }

    public function array(): array
    {
        return $this->mapSchools();
    }

    public function headings(): array
    {
        return [
            'school_name',
            'prefix_name',
            'first_name',
            'middle_name',
            'last_name',
            'suffix_name',
            'full_name',
            'email',
            'phone_cell',
            'phone_work',
            'registrant#',
            'due',
            'paid',
            'status',
        ];
    }

    private function mapSchools(): array
    {
        $a = [];

        foreach ($this->schools as $school) {
            $a[] = [
                'school_name' => $school->schoolName,
                'prefix_name' => $school->prefix_name,
                'first_name' => $school->first_name,
                'middle_name' => $school->middle_name,
                'last_name' => $school->last_name,
                'suffix_name' => $school->suffix_name,
                'full_name' => $school->name,
                'email' => $school->email,
                'phone_cell' => $school->phoneMobile,
                'phone_work' => $school->phoneWork,
                'registrant#' => $school->candidateCount,
                'due' => $this->getPaymentsDue($school->schoolId),
                'paid' => $this->getAmountPaid($school->schoolId),
                'status' => $this->getPaymentStatus($school->schoolId),
            ];
        }

        return $a;
    }

    private function getAmountPaid($schoolId): string
    {
        return $this->payments[$schoolId];
    }

    private function getPaymentsDue($schoolId): string
    {
        return $this->paymentsDue[$schoolId];
    }

    private function getPaymentStatus($schoolId): string
    {
        return $this->paymentStatus[$schoolId];
    }

}
