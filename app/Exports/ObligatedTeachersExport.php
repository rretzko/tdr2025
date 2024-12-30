<?php

namespace App\Exports;

use App\Models\Events\Versions\VersionConfigMembership;
use App\Models\UserConfig;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ObligatedTeachersExport implements FromArray, WithHeadings
{
    public function __construct(
        private readonly bool $membershipCardRequired,
        private readonly int $versionId,
        private readonly array $array
    )
    {
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->array;
    }

    public function headings(): array
    {
        $a = [
            'accepted',
            'prefix_name',
            'first_name',
            'middle_name',
            'last_name',
            'suffix_name',
            'full_name',
            'school_name',
            'grades',
            'email',
            'phoneCell',
            'phoneWork',
        ];

        if ($this->membershipCardRequired) {
            $a[] = 'expiration';
        }

        return $a;
    }
}
