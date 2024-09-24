<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Models\Events\Versions\VersionConfigTimeslot;
use Carbon\Carbon;

class TimeslotAssignmentComponent extends BasePage
{
    public string $endTime = '';
    public string $startTime = '';
    public int $duration = 0;
    public string $successDuration = '';
    public string $successEndTime = '';
    public string $successStartTime = '';
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['versionId'];

        $this->setTimeslotConfigurations();
    }

    private function setTimeslotConfigurations(): void
    {
        $configs = VersionConfigTimeslot::firstOrCreate(
            [
                'version_id' => $this->versionId
            ]
        );

        $this->duration = $configs->duration ?? 1;
        $this->endTime = Carbon::parse($configs->end_time)->subHours(5)->format('Y-m-d H:i:s') ?? '';
        $this->startTime = Carbon::parse($configs->start_time)->subHours(5)->format('Y-m-d H:i:s') ?? '';
    }

    public function render()
    {
        return view('livewire..events.versions.timeslot-assignment-component');
    }

    public function updatedDuration(): void
    {
        $this->reset('successDuration');

        VersionConfigTimeslot::updateOrCreate(
            [
                'version_id' => $this->versionId,
            ],
            [
                'duration' => $this->duration,
            ]
        );

        $this->successDuration = 'Updated.';
    }

    public function updatedEndTime(): void
    {
        $this->reset('successEndTime');

        //convert time to UTC timezon
        $utcTime = Carbon::parse($this->endTime, 'UTC');

        VersionConfigTimeslot::updateOrCreate(
            [
                'version_id' => $this->versionId,
            ],
            [
                'end_time' => Carbon::parse($utcTime),
            ]
        );

        $this->successEndTime = 'Updated.';
    }

    public function updatedStartTime(): void
    {
        $this->reset('successStartTime');

        VersionConfigTimeslot::updateOrCreate(
            [
                'version_id' => $this->versionId,
            ],
            [
                'start_time' => $this->startTime,
            ]
        );

        $this->successStartTime = 'Updated.';
    }
}
