<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use App\Services\TabroomTrackingBulletsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TabroomTrackingComponent extends BasePage
{
    public array $barFormats = [];
    public int $versionId = 0;
    public int $roomId = 0;
    public array $roomList = [];
    public int $studentCount = 0;
    public array $judgeList = [];

    public function mount(): void
    {
        parent::mount();

        $this->barFormats = self::BARFORMATS;
        $this->versionId = UserConfig::getValue('versionId');
        $this->roomList = $this->getRoomList();
        $this->roomId = $this->roomList[0]->id;
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-tracking-component',
            [
                'judgeProgress' => $this->getJudgeProgress(),
                'rooms' => $this->getCandidatesByRoom(),
                'progress' => $this->getProgress(),
            ]);
    }

    private function getCandidatesByRoom(): array
    {
        $service = new TabroomTrackingBulletsService($this->versionId, $this->roomId, $this->barFormats);

        $this->studentCount = $service->studentCount;
        return $service->getCandidates();
    }

    private function getJudgeProgress(): array
    {
        $room = Room::find($this->roomId);
        if (!$room) {
            return [];
        }

        $judges = $room->judges;
        $candidateIds = $room->getRegistrantIds();
        $countRegistrants = count($candidateIds);
        $factorCount = $room->scoringFactors->count();
        $judgeIds = $judges->pluck('id')->all();

        $countsByJudge = [];
        if (!empty($candidateIds) && !empty($judgeIds)) {
            $rawCounts = DB::table('scores')
                ->whereIn('candidate_id', $candidateIds)
                ->whereIn('judge_id', $judgeIds)
                ->selectRaw('candidate_id, judge_id, COUNT(*) AS cnt')
                ->groupBy('candidate_id', 'judge_id')
                ->get();

            foreach ($rawCounts as $row) {
                $countsByJudge[$row->judge_id][$row->candidate_id] = (int) $row->cnt;
            }
        }

        $progress = [];
        foreach ($judges as $judge) {
            $rows = $countsByJudge[$judge->id] ?? [];
            $completed = 0;
            $wip = 0;
            foreach ($rows as $cnt) {
                if ($cnt === $factorCount) {
                    $completed++;
                } elseif ($cnt < $factorCount) {
                    $wip++;
                }
            }
            $pending = $countRegistrants - $completed - $wip;

            $progress[] = [
                'judgeName' => $judge->user->name,
                'judgeShortName' => $judge->user->last_name.','.substr($judge->user->first_name, 0, 1),
                'completed' => $this->formatProgress($completed, $countRegistrants),
                'pending' => $this->formatProgress($pending, $countRegistrants),
                'wip' => $this->formatProgress($wip, $countRegistrants),
            ];
        }

        return $progress;
    }

    private function formatProgress(int $count, int $total): array
    {
        $pct = $total ? floor(($count / $total) * 100).'%' : '0%';
        return ['count' => $count, 'pct' => $pct];
    }

    private function getProgress(): array
    {
        $registrant = new Registrant(0, $this->versionId);
        $total = $registrant->getCountOfRegistrants();

        $counts = [
            'completed' => $registrant->getCountOfRegistrantsCompleted(),
            'errors' => $registrant->getCountOfRegistrantsOverScored(),
            'total' => $total,
            'wip' => $registrant->getCountOfRegistrantsWip(),
        ];

        $counts['pending'] = ($total - ($counts['completed'] + $counts['errors'] + $counts['wip']));

        $progress = [];
        $progress['total'] = ['count' => $total, 'wpct' => '']; //wpct = width percent
        foreach ($counts as $key => $value) {

            $wpct = $value ? number_format((($value / $total) * 100), 1) : 0;
            $progress[$key] = ['count' => $value, 'wpct' => "$wpct%"];
        }

        return $progress;
    }

    private function getRoomList(): array
    {
        return DB::table('rooms')
            ->where('version_id', $this->versionId)
            ->select('id', 'room_name AS roomName')
            ->orderBy('order_by')
            ->get()
            ->toArray();
    }
}
