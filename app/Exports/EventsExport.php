<?php

namespace App\Exports;

use App\Models\event;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventsExport implements FromQuery, WithHeadings
{
    /**
     * @return Builder
     * @todo build more sophisticated spreadsheet for ensemble voice part descriptions
     */
    public function query(): Builder
    {
        return DB::table('events')
            ->join('event_management', 'event_management.event_id', '=', 'events.id')
            ->join('event_ensembles', 'event_ensembles.event_id', '=', 'events.id')
            ->where('event_management.user_id', auth()->id())
            ->where('event_management.role', 'manager')
            ->select('events.id', 'events.name', 'events.short_name', 'events.organization', 'events.audition_count',
                'events.max_registrant_count', 'events.max_upper_voice_count', 'events.ensemble_count',
                'events.frequency',
                'events.grades', 'events.status', 'events.logo_file', 'events.logo_file_alt', 'events.required_height',
                'events.required_shirt_size',
                'event_ensembles.ensemble_name', 'event_ensembles.ensemble_short_name',
                'event_ensembles.grades AS eGrades', 'event_ensembles.voice_part_ids')
            ->orderBy('events.name');
    }

    public function headings(): array
    {
        return [
            'sysId', 'name', 'short_name', 'organization',
            'auditions/registrant', 'maxRegistrants',
            'maxUpperVoiceRegistrants', 'ensembleCount',
            'frequency', 'grades', 'status', 'logoFile',
            'logoFileAlt', 'requiredHeight', 'requiredShirtSize',
            'ensembleName', 'ensembleShortName', 'ensembleGrades',
            'ensembleVoicePartIds'
        ];
    }
}
