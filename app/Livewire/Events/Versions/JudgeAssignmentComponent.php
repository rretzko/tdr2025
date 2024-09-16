<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\RoomForm;
use App\Models\Events\Versions\Judge;
use App\Models\Events\Versions\Scoring\Room;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionScoring;
use App\Models\UserConfig;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JudgeAssignmentComponent extends BasePage
{
    public RoomForm $form;
    public array $columnHeaders = [];
    public array $members = [];
    public bool $showForm = false;
    public array $tolerances = [];
    public int $versionId = 0;
    public Version $version;
    public array $versionScoreCategories = [];
    public Collection $voiceParts;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($this->versionId);

        $this->columnHeaders = $this->getColumnHeaders();
        $this->versionScoreCategories = $this->getVersionScoreCategories();
        $this->members = $this->getMembers();
        $this->tolerances = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $this->voiceParts = $this->version->event->voiceParts;

        if (!$this->hasRows()) {
            $this->cloneRooms();
        }
    }

    public function render()
    {
        return view('livewire.events.versions.judge-assignment-component',
            [
                'rows' => $this->getRows(),
                'roomVoiceParts' => $this->getRoomVoiceParts(),
                'roomScoreCategories' => $this->getRoomScoreCategories(),
                'roomJudges' => $this->getRoomJudges(),
            ]);
    }

    public function edit(int $roomId): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        $this->showForm = (bool) $this->form->setRoom($roomId);
    }

    public function remove(int $roomId): void
    {
        dd(__METHOD__);
    }

    public function save(): void
    {
        $roomName = $this->form->roomName;
        $this->form->save();

        $this->showSuccessIndicator = true;
        $this->successMessage = $roomName.' has been saved.';

        $this->dispatch('savedRoomForm', auth()->id());
    }

    /**
     * Use the most previous version to seed rows for the current version
     * @return void
     */
    private function cloneRooms(): void
    {
        $previousVersionRooms = $this->version->event->versions[1]->rooms;

        foreach ($previousVersionRooms as $oldRoom) {

            $newRoom = Room::create(
                [
                    'version_id' => $this->versionId,
                    'room_name' => $oldRoom->room_name,
                    'tolerance' => $oldRoom->tolerance,
                    'order_by' => $oldRoom->order_by,
                ]
            );

            //clone the voice parts from the most previous version to the new version
            $this->cloneRoomVoiceParts($newRoom, $oldRoom);

            //clone the score categories from the most previous version to the new version
            $this->cloneRoomScoreCategories($newRoom, $oldRoom);
        }
    }

    private function cloneRoomScoreCategories(Room $newRoom, Room $oldRoom): void
    {
        //early exit if $newRoom contains score_categories_ids
        if (RoomScoreCategory::where('room_id', $newRoom->id)->first()) {
            return;
        }

        //early exit if $oldRoom doesn't have any voice parts
        if (!RoomScoreCategory::where('room_id', $oldRoom->id)->first()) {
            return;
        }

        $roomScoreCategories = RoomScoreCategory::where('room_id', $oldRoom->id)->get();
        foreach ($roomScoreCategories as $roomScoreCategory) {

            RoomScoreCategory::create(
                [
                    'room_id' => $newRoom->id,
                    'score_category_id' => $roomScoreCategory->score_category_id,
                ]
            );
        }
    }

    private function cloneRoomVoiceParts(Room $newRoom, Room $oldRoom): void
    {
        //early exit if $newRoom contains voice_part_ids
        if (RoomVoicePart::where('room_id', $newRoom->id)->first()) {
            return;
        }

        //early exit if $oldRoom doesn't have any voice parts
        if (!RoomVoicePart::where('room_id', $oldRoom->id)->first()) {
            return;
        }

        $roomVoiceParts = RoomVoicePart::where('room_id', $oldRoom->id)->get();
        foreach ($roomVoiceParts as $roomVoicePart) {

            RoomVoicePart::create(
                [
                    'room_id' => $newRoom->id,
                    'voice_part_id' => $roomVoicePart->voice_part_id,
                ]
            );
        }
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => null],
            ['label' => 'voice parts', 'sortBy' => null],
            ['label' => 'score categories', 'sortBy' => null],
            ['label' => 'judges', 'sortBy' => null],
            ['label' => 'tolerance', 'sortBy' => null],
        ];
    }

    private function getMembers(): array
    {
        $statuses = ['invited', 'obligated', 'participating'];

        return DB::table('version_participants')
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->where('version_id', $this->versionId)
            ->whereIn('status', $statuses)
            ->select(DB::raw("CONCAT(users.last_name,', ',users.first_name) AS fullName"),
                'users.id', 'users.last_name', 'users.first_name')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->pluck('fullName', 'users.id')
            ->toArray();
    }

    private function getRoomIds(): array
    {
        static $roomIds = [];

        if (empty($roomIds)) {
            $roomIds = Room::where('version_id', $this->versionId)->pluck('id')->toArray();
        }

        return $roomIds;
    }

    private function getRoomJudges(): array
    {
        $a = [];
        $roomIds = $this->getRoomIds();

        foreach ($roomIds as $roomId) {

            $a[$roomId] = implode(', ', Judge::query()
                ->join('users', 'users.id', '=', 'judges.user_id')
                ->where('judges.room_id', $roomId)
                ->select('users.name', 'judges.judge_type')
                ->orderBy('judges.judge_type')
                ->pluck('users.name')
                ->toArray());
        }

        return $a;
    }

    private function getRoomScoreCategories(): array
    {
        $a = [];
        $roomIds = $this->getRoomIds();

        foreach ($roomIds as $roomId) {

            $a[$roomId] = implode(', ', RoomScoreCategory::query()
                ->join('score_categories', 'score_categories.id', '=', 'room_score_categories.score_category_id')
                ->where('room_score_categories.room_id', $roomId)
                ->select('score_categories.descr')
                ->orderBy('score_categories.order_by')
                ->pluck('score_categories.descr')
                ->toArray());
        }

        return $a;
    }

    private function getRoomVoiceParts(): array
    {
        $a = [];
        $roomIds = $this->getRoomIds();

        foreach ($roomIds as $roomId) {

            $a[$roomId] = implode(', ', RoomVoicePart::query()
                ->join('voice_parts', 'voice_parts.id', '=', 'room_voice_parts.voice_part_id')
                ->where('room_voice_parts.room_id', $roomId)
                ->select('voice_parts.descr')
                ->pluck('voice_parts.descr')
                ->toArray());
        }

        return $a;
    }

    private function getRows(): Collection
    {
        return DB::table('rooms')
            ->where('rooms.version_id', $this->versionId)
            ->orderBy('rooms.order_by')
            ->get();
    }

    private function getVersionScoreCategories(): array
    {
        $scoreCategories = ScoreCategory::query()
            ->where('version_id', $this->versionId)
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();

        if (!$scoreCategories) {

            $scoreCategories = ScoreCategory::query()
                ->where('event_id', $this->version->event->id)
                ->orderBy('order_by')
                ->pluck('descr', 'id')
                ->toArray();
        }

        return $scoreCategories;
    }

    private function hasRows(): bool
    {
        return Room::where('version_id', $this->versionId)->exists();
    }
}
