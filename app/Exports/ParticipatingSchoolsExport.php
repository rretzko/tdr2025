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
        private readonly int $versionId,
        private readonly array $schoolIds,
    ) {
        $this->baseArray = $this->getBaseArray();
    }

    /**
     * @return mixed[]
     */
    public function getBaseArray(): array
    {
        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->distinct(['candidates.school_id', 'candidates.teacher_id'])
            ->select('schools.name AS schoolName', 'schools.id AS schoolId',
                'users.prefix_name', 'users.last_name', 'users.middle_name', 'users.first_name', 'users.suffix_name',
                'users.name',
                DB::raw('COUNT(candidates.id) AS candidateCount'))
            ->groupBy(
                'candidates.school_id',
                'candidates.teacher_id',
                'schools.name',
                'users.prefix_name',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.suffix_name',
                'users.name',
                'schoolId'
            )
            ->orderBy('schools.name')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();

    }

    public function array(): array
    {
        $a = [];

        foreach ($this->baseArray as $base) {

            $schoolId = $base->schoolId;

            $a[] = [
                $base->schoolName,
                $base->prefix_name,
                $base->last_name,
                $base->first_name,
                $base->middle_name,
                $base->suffix_name,
                $base->name,
                $base->candidateCount,
                $this->paymentsDue[$schoolId],
                $this->payments[$schoolId],
            ];
        }

        return $a;
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
            'registrant#',
            'due',
            'paid'
        ];
    }

    private function getPaymentsDue(): array
    {
        $dues = [];
        $feeRegistration = Version::find($this->versionId)->fee_registration;

        foreach ($this->schoolIds as $schoolId) {

            $candidateCount = Candidate::query()
                ->where('school_id', $schoolId)
                ->where('version_id', $this->versionId)
                ->where('status', 'registered')
                ->count('id');

            $dues[$schoolId] = ($candidateCount * $feeRegistration);
        }

        return array_map(function ($value) {
            return ConvertToUsdService::penniesToUsd($value);
        }, $dues);
    }

    private function getPaymentsStatus(array $payments, array $paymentsDue): array
    {
        $paymentStatuses = [];

        foreach ($this->schoolIds as $schoolId) {

            $balance = ($paymentsDue[$schoolId] - $payments[$schoolId]);

            $paymentStatuses[$schoolId] = match (true) {
                (!$balance) => 'paid',
                ($balance > 0) => 'due',
                ($balance < 0) => 'refund',
                'default' => 'error',
            };
        }

        return $paymentStatuses;
    }

    private function getSchoolIds(): array
    {
        return Candidate::where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->distinct('school_id')
            ->orderBy('school_id')
            ->pluck('school_id')
            ->toArray();
    }
}
