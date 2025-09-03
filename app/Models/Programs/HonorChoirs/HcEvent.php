<?php

namespace App\Models\Programs\HonorChoirs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HcEvent extends Model
{
    /** @use HasFactory<\Database\Factories\Programs\HonorChoirs\HcEventFactory> */
    use HasFactory;

    protected $fillable = [
        'hc_organization_id',
        'image_link',
        'name',
        'program_link',
        'video_link',
        'year_of',
    ];

    public function cleanImageLink(): string
    {
        if (empty($this->image_link)) {
            return '';
        }

        return Str::afterLast($this->image_link, '/');
    }

    public function compositions(): array
    {
        return HcLibrary::where('hc_event_id', $this->id)->select('title', 'subtitle', 'artist')->get()->toArray();
    }

    public function conductorNamesArray(): array
    {
        return \DB::table('hc_conductors')
            ->join('hc_conductor_event', 'hc_conductors.id', '=', 'hc_conductor_event.hc_conductor_id')
            ->where('hc_conductor_event.hc_event_id', '=', $this->id)
            ->pluck('hc_conductors.name')
            ->toArray() ?? [];
    }

    public function getParticipantInstrumentOrderBys(): array
    {
        return DB::table('hc_participants')
            ->where('hc_event_id', $this->id)
            ->select('instrument_order_by', 'instrument_name')
            ->orderBy('instrument_order_by')
            ->distinct()
            ->get()
            ->toArray() ?? [];
    }

    public function getParticipants(int $instrumentOrderBy): array
    {
        return DB::table('hc_participants')
            ->where('hc_event_id', $this->id)
            ->where('instrument_order_by', $instrumentOrderBy)
            ->select('full_name', 'school_name')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->toArray() ?? [];
    }

    public function nextEventId(): int
    {
        return $this->getAdjacentEventId(1);
    }

    public function previousEventId(): int
    {
        return $this->getAdjacentEventId(-1);
    }

    private function getAdjacentEventId(int $offset): int
    {
        $eventIds = $this->getEventIds();
        $currentIndex = array_search($this->id, $eventIds, true);

        if($currentIndex === false) {
            return 0;
        }

        $adjacentIndex = $currentIndex + $offset;

        return $eventIds[$adjacentIndex] ?? 0;
    }

    private function getEventIds(): array
    {
        // Cache the result to avoid multiple DB queries if needed
        if (!isset($this->cachedEventIds)) {
            $this->cachedEventIds = HcEvent::where('hc_organization_id', $this->hc_organization_id)
                ->orderBy('year_of', 'desc')
                ->pluck('id')
                ->toArray();
        }

        return $this->cachedEventIds;
    }
}
