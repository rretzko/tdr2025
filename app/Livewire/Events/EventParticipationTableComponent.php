<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use App\Mail\RequestInvitationToEventMail;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EventParticipationTableComponent extends BasePage
{
    public function mount(): void
    {
        //ensure a schoolId is set
        if (!strlen(UserConfig::getValue('schoolId'))) {
            UserConfig::setProperty('schoolId', auth()->user()->teacher->schools->first()->id);
        }
    }

    public function render()
    {
        return view('livewire..events.event-participation-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(),
            ]);
    }

    public function requestInvitation(int $versionId): void
    {
        $version = Version::find($versionId);

        Mail::to($version->getVersionManager()->email)
            ->send(new RequestInvitationToEventMail($version));

        $this->showSuccessIndicator = true;
        $this->successMessage = 'Event invitation request has been sent!';
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'event', 'sortBy' => null],
            ['label' => 'version', 'sortBy' => null],
            ['label' => 'status', 'sortBy' => null],
        ];
    }

    private function getRows(): array
    {
        $invited = $this->getRowsInvited();
        $past = $this->getRowsPast();
        $sandbox = $this->getRowsSandbox();
        $potential = $this->getRowsPotential();

        return array_merge($invited, $potential, $past, $sandbox);
    }

    private function getRowsInvited(): array
    {
        //$this->test();

        return DB::table('version_participants')
            ->join('versions', 'versions.id', '=', 'version_participants.version_id')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->where('version_participants.user_id', auth()->id())
            ->where('versions.status', 'active')
            ->select('version_participants.version_id AS id',
                'events.short_name AS eventName',
                'versions.short_name AS versionName', 'versions.status',
                'versions.senior_class_of'
            )
            ->orderByDesc('versions.senior_class_of')
            ->get()
            ->toArray();
    }

    private function getRowsPast(): array
    {
//        $this->test();
        return DB::table('version_participants')
            ->join('versions', 'versions.id', '=', 'version_participants.version_id')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->join('candidates', 'candidates.version_id', '=', 'version_participants.version_id')
            ->where('version_participants.user_id', auth()->id())
            ->where('versions.status', 'closed')
            ->distinct('versions.id')
            ->select('version_participants.version_id AS id',
                'events.short_name AS eventName',
                'versions.short_name AS versionName', 'versions.status',
                'versions.senior_class_of'
            )
            ->orderByDesc('versions.senior_class_of')
            ->get()
            ->toArray();
    }

    /**
     * Active versions to which the user has NOT been invited but could
     * potentially participate in.
     * Functionality allows user to request an invitation from the event-version's
     * manager.
     *
     * @return array
     */
    private function getRowsPotential(): array
    {
        //$this->test();

        $rows = DB::table('versions')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->join('version_participants', 'version_participants.version_id', '=', 'versions.id')
            ->join('version_roles', 'version_roles.version_participant_id', '=', 'version_participants.id')
            ->where('versions.status', 'active')
            ->where('version_participants.user_id', '<>', auth()->id())
            ->distinct('versions.id')
            ->select('version_participants.version_id AS id',
                'events.short_name AS eventName',
                'versions.short_name AS versionName', 'versions.status',
                'versions.senior_class_of'
            )
            ->orderByDesc('versions.senior_class_of')
            ->get()
            ->toArray();

        foreach ($rows as $row) {

            $row->status = 'request';
        }

        return $rows;
    }

    private function getRowsSandbox(): array
    {
        //$this->test();

        return DB::table('versions')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->join('version_participants', 'version_participants.version_id', '=', 'versions.id')
            ->join('version_roles', 'version_roles.version_participant_id', '=', 'version_participants.id')
            ->where('versions.status', 'sandbox')
            ->where('version_participants.user_id', auth()->id())
            ->distinct('versions.id')
            ->select('version_participants.version_id AS id',
                'events.short_name AS eventName',
                'versions.short_name AS versionName', 'versions.status',
                'versions.senior_class_of'
            )
            ->orderByDesc('versions.senior_class_of')
            ->get()
            ->toArray();
    }
}
