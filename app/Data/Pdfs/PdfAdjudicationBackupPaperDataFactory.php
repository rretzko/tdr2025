<?php

namespace App\Data\Pdfs;

use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\Room;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PdfAdjudicationBackupPaperDataFactory
{
    private array $dto = [];
    private array $rooms;

    public function __construct(private int $roomId)
    {
        $this->init();
    }

    private function init(): void
    {
        $this->rooms = $this->getRooms();

        //rooms
        $this->dto['rooms'] = $this->rooms;

        //judges
        foreach ($this->dto['rooms'] as $key => $room) {
            $this->dto['rooms'][$key]['judges'] = $this->getJudges($room['id']);
        }

        //categories
        foreach ($this->dto['rooms'] as $key => $room) {
            $this->dto['rooms'][$key]['scoreCategories'] = $this->getScoreCategories($room['id']);
        }

        //factors
        foreach ($this->dto['rooms'] as $key => $room) {
            $this->dto['rooms'][$key]['scoreFactors'] = $this->getScoreFactors($room['id']);
        }

        //registrants
        foreach ($this->dto['rooms'] as $key => $room) {
            $this->dto['rooms'][$key]['registrants'] = $this->getRegistrants($room['id']);
        }

        //score sheet page count per judge
        $maxRegistrantsPerPage = 35;
        $this->dto['rooms'][$key]['pageCount'] = (ceil(count($this->dto['rooms'][$key]['registrants']) / $maxRegistrantsPerPage));

        $this->dto['versionName'] = $this->getVersionName();
    }

    /**
     * Uses $this->dto['rooms'\
     * @return array
     */
    private function getJudges(int $roomId): array
    {
        $judges = [];

        $room = Room::find($roomId);

        $judges[$room->id] = DB::table('judges')
            ->join('users', 'users.id', '=', 'judges.user_id')
            ->where('judges.room_id', $room->id)
            ->where('judges.status_type', 'assigned')
            ->whereNot('judges.judge_type', 'monitor')
            ->select('judges.id', 'users.id AS userId', 'users.name', 'judges.judge_type', 'users.last_name')
            ->orderBy('judges.judge_type')
            ->orderBy('users.last_name')
            ->get()
            ->toArray();

        return $judges;
    }

    private function getRegistrants(int $roomId): array
    {
        $room = Room::find($roomId);

        return $room->registrantsById->toArray();
    }

    private function getRoomData(Room $room): array
    {
        return [
            'id' => $room->id,
            'roomName' => $room->room_name,
            'tolerance' => $room->tolerance,
        ];
    }

    private function getRooms(): array
    {
        $roomData = [];

        //return single object within a model for consistency
        if ($this->roomId) {
            $room = Room::find($this->roomId);
            $roomData[$this->roomId] = $this->getRoomData($room);

        } else {// return all room objects belonging to a $versionId

            $versionId = UserConfig::getValue('versionId');
            $rooms = Room::where('version_id', $versionId)->get();
            foreach ($rooms as $room) {
                $roomData[$room->id] = $this->getRoomData($room);
            }
        }

        return $roomData;
    }

    private function getScoreCategories(int $roomId): array
    {
        return RoomScoreCategory::query()
            ->join('score_categories', 'score_categories.id', '=', 'room_score_categories.score_category_id')
            ->join('score_factors', 'score_factors.score_category_id', '=', 'score_categories.id')
            ->where('room_id', $roomId)
            ->select(
                'score_categories.descr',
                'score_categories.order_by',
                DB::raw('count(score_factors.id) AS colSpan')
            )
            ->groupBy('score_categories.order_by')
            ->groupBy('score_categories.descr',)
            ->orderBy('score_categories.order_by')
            ->orderBy('score_categories.descr')
            ->get()
            ->toArray();
    }

    private function getScoreFactors(int $roomId): array
    {
        $factors = RoomScoreCategory::query()
            ->join('score_categories', 'score_categories.id', '=', 'room_score_categories.score_category_id')
            ->join('score_factors', 'score_factors.score_category_id', '=', 'score_categories.id')
            ->where('room_id', $roomId)
            ->select(
                'score_factors.abbr',
                'score_factors.order_by'
            )
            ->orderBy('score_factors.order_by')
            ->orderBy('score_factors.abbr')
            ->get()
            ->toArray();

        $this->dto['rooms'][$roomId]['factorCount'] = count($factors);

        return $factors;
    }


    private function getVersionName(): string
    {
        return Version::find(UserConfig::getValue('versionId'))->name;

    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
