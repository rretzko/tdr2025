<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    // ROOM VARs
    public int $orderBy = 1;
    public string $roomName = '';
    public array $scoreCategoryIds = [];
    public int $sysId = 0;
    public int $tolerance = 0;
    public int $userId = 0;
    public int $versionId = 0;
    public array $voicePartIds = [];

    //JUDGE VARs
    public int $headJudge = 0;
    public int $judge2 = 0;
    public int $judge3 = 0;
    public int $judge4 = 0;
    public int $judgeMonitor = 0;
    public int $monitor = 0;

    public function resetVariables(): void
    {
        $this->reset('orderBy', 'roomName', 'scoreCategoryIds', 'sysId',
            'tolerance', 'userId', 'versionId', 'voicePartIds');

        $this->reset('headJudge', 'judge2', 'judge3', 'judge4', 'judgeMonitor', 'monitor');
    }

    public function save(int $versionId): bool
    {
        return ($this->sysId)
            ? $this->updateRoom()
            : $this->addRoom($versionId);
    }

    public function setRoom(int $roomId): bool
    {
        $this->sysId = $roomId;
        $room = Room::find($this->sysId);
        $this->orderBy = $room->order_by;
        $this->roomName = $room->room_name;
        $this->tolerance = $room->tolerance;
        $this->versionId = $room->version_id;

        $this->scoreCategoryIds = $this->getScoreCategoryIds();

        $this->voicePartIds = RoomVoicePart::query()
            ->where('room_id', $this->sysId)
            ->pluck('voice_part_id')
            ->toArray();

        $this->setRoomJudges($room->version_id);

        return true;
    }

    public function updateJudge(string $judgeType): bool
    {
        $var = Str::camel($judgeType);

        return (bool) Judge::updateOrCreate(
            [
                'version_id' => $this->versionId,
                'room_id' => $this->sysId,
                'judge_type' => $judgeType,
            ],
            [
                'user_id' => $this->$var,
                'status_type' => 'assigned',
            ]
        );
    }

    public function updateRoom(): bool
    {
        $room = Room::find($this->sysId);

        $room->update(
            [
                'room_name' => $this->roomName,
                'tolerance' => $this->tolerance,
                'order_by' => $this->orderBy,
            ]
        );

        $this->addRoomScoreCategories($room->id);

        $this->addRoomVoiceParts($room->id);

        return true;
    }

    public function addRoom(int $versionId): bool
    {
        return DB::transaction(function () use ($versionId) {
            $room = Room::create(
                [
                    'order_by' => $this->orderBy,
                    'room_name' => $this->roomName,
                    'tolerance' => $this->tolerance,
                    'version_id' => $versionId,
                ]
            );

            //early exit
            if (!$room) {
                return false;
            }

            $this->sysId = $room->id;

            $this->addRoomJudges();
            $this->addRoomScoreCategories();
            $this->addRoomVoiceParts();

            return true;
        });

    }

    private function addRoomJudge(string $judgeType): void
    {
        Judge::updateOrCreate(
            [
                'judge_type' => $judgeType,
                'room_id' => $this->sysId,
                'version_id' => $this->versionId,
            ],
            [
                'status_type' => 'assigned',
                'user_id' => auth()->id(),
            ]
        );
    }

    private function addRoomJudges(): void
    {
        $judges = ['headJudge', 'judge2', 'judge3', 'judge4', 'judgeMonitor', 'monitor'];

        foreach ($judges as $judge) {
            if ($this->$judge) {
                $this->addRoomJudge($judge, $this->sysId);
            }
        }
    }

    private function addRoomScoreCategories(): void
    {
        $roomId = $this->sysId;

        // Start a transaction
        DB::transaction(function () use ($roomId) {
            // Ensure none exist by directly deleting
            RoomScoreCategory::where('room_id', $roomId)->delete();

            // Insert new records
            foreach ($this->scoreCategoryIds as $scoreCategoryId) {
                RoomScoreCategory::create([
                    'room_id' => $roomId,
                    'score_category_id' => $scoreCategoryId,
                ]);
            }
        });

    }

    private function addRoomVoiceParts(): void
    {
        $roomId = $this->sysId;

        // Start a transaction
        DB::transaction(function () use ($roomId) {
            // Ensure none exist by directly deleting
            RoomVoicePart::where('room_id', $roomId)->delete();

            // Insert new records
            foreach ($this->voicePartIds as $voicePartId) {
                RoomVoicePart::create([
                    'room_id' => $roomId,
                    'voice_part_id' => $voicePartId,
                ]);
            }
        });
    }

    private function getScoreCategoryIds(): array
    {
        return RoomScoreCategory::query()
            ->where('room_id', $this->sysId)
            ->pluck('score_category_id')
            ->toArray() ?? [];
    }

    private function setRoomJudges(int $versionId): void
    {
        $query = Judge::query()
            ->where('version_id', $versionId)
            ->where('room_id', $this->sysId);

        $this->headJudge = $query->clone()
            ->where('judge_type', 'head judge')
            ->value('user_id') ?? 0;

        $this->judge2 = $query->clone()
            ->where('judge_type', 'judge 2')
            ->value('user_id') ?? 0;

        $this->judge3 = $query->clone()
            ->where('judge_type', 'judge 3')
            ->value('user_id') ?? 0;

        $this->judge4 = $query->clone()
            ->where('judge_type', 'judge 4')
            ->value('user_id') ?? 0;

        $this->judgeMonitor = $query->clone()
            ->where('judge_type', 'judge monitor')
            ->value('user_id') ?? 0;

        $this->monitor = $query->clone()
            ->where('judge_type', 'monitor')
            ->value('user_id') ?? 0;
    }

}
