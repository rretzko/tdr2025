<?php

namespace App\Exports;


use App\Models\Events\Versions\VersionScoring;
use App\Models\UserConfig;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VersionScoringExport implements FromQuery, WithHeadings
{
    public function query(): Builder
    {
        $versionId = UserConfig::getValue('versionId');

        return VersionScoring::query()
            ->where('version_id', $versionId)
            ->select('file_type', 'segment', 'abbr',
                'order_by', 'best', 'worst', 'multiplier', 'tolerance')
            ->orderBy('version_scorings.order_by', 'asc');
    }

    public function headings(): array
    {
        return [
            'file type', 'segment', 'abbr', 'order',
            'best', 'worst', 'multiplier', 'tolerance'
        ];
    }
}
