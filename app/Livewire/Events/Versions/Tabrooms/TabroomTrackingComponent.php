<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Room;
use App\Models\UserConfig;
use App\Services\TabroomTrackingBulletsService;
use Illuminate\Support\Facades\Log;

class TabroomTrackingComponent extends BasePage
{
    public int $versionId;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-tracking-component',
            [
                'rooms' => $this->getCandidatesByRoom(),
                'progress' => $this->getProgress(),
            ]);
    }

    private function getCandidatesByRoom(): array
    {
        $service = new TabroomTrackingBulletsService($this->versionId);

        return $service->getCandidates();
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
        $progress['total'] = ['count' => $total, 'wpct' => ''];
        foreach ($counts as $key => $value) {

            $wpct = $value ? floor(($value / $total) * 100) : 0;
            $progress[$key] = ['count' => $value, 'wpct' => "w-$wpct/100"];
            Log::info($key.' => '.$value);
        }
        Log::info('===========================================');
        return $progress;
    }
}
