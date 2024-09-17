<?php

namespace App\Livewire\Events\Versions;

use App\Exports\JudgeAssignmentsExport;
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
use App\ValueObjects\PreviousJudgingHistoryVO;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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

    //judge flags
    public bool $headJudgeSaved = false;
    public bool $judge2Saved = false;
    public bool $judge3Saved = false;
    public bool $judge4Saved = false;
    public bool $judgeMonitorSaved = false;
    public bool $monitorSaved = false;

    //previous history
    public string $previousHistoryHeadJudge = '';
    public string $previousHistoryJudge2 = '';
    public string $previousHistoryJudge3 = '';
    public string $previousHistoryJudge4 = '';
    public string $previousHistoryJudgeMonitor = '';
    public string $previousHistoryMonitor = '';

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

    public function add(): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        $this->form->resetVariables();

        $this->showForm = (!$this->showForm);
    }

    public function edit(int $roomId): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        $this->showForm = (bool) $this->form->setRoom($roomId);

        //ensure that required judge user_ids are available from $this->form
        if ($this->showForm) {
            $this->loadPreviousHistories();
        }
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //clear any artifacts
        $this->reset('search');

        return Excel::download(new JudgeAssignmentsExport(), 'judgeAssignments.csv');
    }

    public function remove(int $roomId): void
    {
        $room = Room::find($roomId);

        if ($room) {

            $this->removeRoomJudges($roomId);
            $this->removeRoomScoreCategories($roomId);
            $this->removeRoomVoiceParts($roomId);

            $roomName = $room->room_name;
            $room->delete();

            $this->showSuccessIndicator = true;
            $this->successMessage = 'Room: '.$roomName.' has been removed.';
        }
    }

    private function removeRoomJudges(int $roomId): void
    {
        Judge::where('room_id', $roomId)->delete();
    }

    private function removeRoomScoreCategories(int $roomId): void
    {
        RoomScoreCategory::where('room_id', $roomId)->delete();
    }

    private function removeRoomVoiceParts(int $roomId): void
    {
        RoomVoicePart::where('room_id', $roomId)->delete();
    }

    public function save(): void
    {
        $addingNewRoom = ($this->form->sysId == 'new');
        $roomName = $this->form->roomName;
        $this->form->save($this->versionId);

        $this->showSuccessIndicator = true;
        $this->successMessage = ($addingNewRoom)
            ? $roomName.' has been added.'
            : $roomName.' has been saved.';

        //switch to edit mode
        if ($addingNewRoom) {
            $this->edit($this->form->sysId);
        }

        $this->dispatch('savedRoomForm', auth()->id());
    }

    public function updatedFormHeadJudge(): void
    {
        $this->headJudgeSaved = $this->form->updateJudge('head judge');

        $vo = new PreviousJudgingHistoryVO($this->version, $this->form->headJudge);

        $this->previousHistoryHeadJudge = $vo->getHistory();
    }

    public function updatedFormJudge2(): void
    {
        $this->judge2Saved = $this->form->updateJudge('judge 2');

        $vo = new PreviousJudgingHistoryVO($this->version, $this->form->judge2);

        $this->previousHistoryJudge2 = $vo->getHistory();
    }

    public function updatedFormJudge3(): void
    {
        $this->judge3Saved = $this->form->updateJudge('judge 3');

        $vo = new PreviousJudgingHistoryVO($this->version, $this->form->judge3);

        $this->previousHistoryJudge3 = $vo->getHistory();
    }

    public function updatedFormJudge4(): void
    {
        $this->judge4Saved = $this->form->updateJudge('judge 4');

        $vo = new PreviousJudgingHistoryVO($this->version, $this->form->judge4);

        $this->previousHistoryJudge4 = $vo->getHistory();
    }

    public function updatedFormJudgeMonitor(): void
    {
        $this->judgeMonitorSaved = $this->form->updateJudge('judge monitor');

        $vo = new PreviousJudgingHistoryVO($this->version, $this->form->judgeMonitor);

        $this->previousHistoryJudgeMonitor = $vo->getHistory();
    }

    public function updatedFormMonitor(): void
    {
        $this->monitorSaved = $this->form->updateJudge('monitor');

        $vo = new PreviousJudgingHistoryVO($this->version, $this->form->judgeMonitor);

        $this->previousHistoryJudgeMonitor = $vo->getHistory();
    }

    private function abbreviateJudgeType(string $judgeType): string
    {
        //early exit
        if (empty(trim($judgeType))) {
            return 'none';
        }

        $parts = explode(' ', $judgeType);

        return implode('', array_map(fn($part) => $part[0], $parts));;
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

    private function formatJudgesByRoom(array $judgesByRoom): array
    {
        $formattedJudges = [];

        foreach ($judgesByRoom as $roomId => $judges) {
            if (count($judges)) {
                $formattedJudges[$roomId] = array_map(function ($judge) {
                    return $judge['name']
                        .' ('
                        .$this->abbreviateJudgeType($judge['judge_type'])
                        .')';
                }, $judges);
            } else {
                $formattedJudges[$roomId] = ['none found'];
            }
        }

        return $formattedJudges;
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

    private function getJudgesByRoom(array $roomIds): array
    {
        $judgesByRoom = [];

        foreach ($roomIds as $roomId) {
            $judgesByRoom[$roomId] = Judge::query()
                ->join('users', 'users.id', '=', 'judges.user_id')
                ->where('judges.room_id', $roomId)
                ->where('version_id', $this->versionId)
                ->select('users.name', 'judges.judge_type')
                ->orderBy('judges.judge_type')
                ->get()
                ->toArray();
        }

        return $judgesByRoom;
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
        $raw = [];
        $roomIds = $this->getRoomIds();
        $judgesByRoom = $this->getJudgesByRoom($roomIds);

        return $this->formatJudgesByRoom($judgesByRoom);
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

    private function loadPreviousHistories(): void
    {
        $judgeTypes = ['headJudge', 'judge2', 'judge3', 'judge4', 'judgeMonitor', 'monitor'];

        foreach ($judgeTypes as $judgeType) {

            if ($this->form->$judgeType) {

                $vo = new PreviousJudgingHistoryVO($this->version, $this->form->$judgeType);

                $previousHistoryType = 'previousHistory'.ucwords(($judgeType));

                $this->$previousHistoryType = $vo->getHistory();
            }
        }

    }
}
