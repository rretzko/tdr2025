<?php

namespace App\Exports;

use App\Models\UserConfig;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PitchFilesExport implements FromQuery, WithHeadings
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return DB::table('version_pitch_files')
            ->join('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
            ->where('version_pitch_files.version_id', UserConfig::getValue('versionId'))
            ->whereNull('deleted_at')
            ->select('voice_parts.descr',
                'version_pitch_files.file_type', 'version_pitch_files.description',
                'version_pitch_files.url', 'version_pitch_files.order_by')
            ->orderBy('version_pitch_files.order_by');
    }

    public function headings(): array
    {
        return [
            'voice part', 'file type', 'description', 'location', 'order',
        ];
    }
}
