<?php

namespace App\Data;

use App\Models\Events\Versions\Version;
use App\Models\PageInstruction;
use App\Models\ViewCard;
use App\Services\VersionsTableService;

class ViewDataFactory extends aViewData
{
    public function __construct(public readonly string $__method, public readonly mixed $id = 0)
    {
        parent::__construct($this->__method);

        $this->init();
    }

    private function init(): void
    {
        //if $id, ensure that $this->dto['id'] is an integer value
        $this->dto['id'] = (($this->id) && (is_object($this->id)))
            ? (int) $this->id->id
            : (int) $this->id;

        //include the count of schools to determine if the breadcrumbs should be included
        //a newly registered user will have NO schools and should not be allowed to do
        //anything else until a school is added
        $this->dto['schoolCount'] = auth()->user()->teacher->schools->count();

        //register pageName, header, and page instructions in $dto
        if ($this->viewPage->id) {

            $this->dto['pageName'] = 'pages.'.$this->viewPage->page_name.'Page';

            $this->dto['header'] = $this->viewPage->header;

            $this->dto['pageInstructions'] = $this->decodeInstructions(PageInstruction::where('header',
                $this->dto['header'])->first()->instructions);

            //retrieve page components ex. cards
            foreach ($this->getComponents() as $component) {

                $method = 'get'.ucfirst($component);

                $this->dto[$component] = $this->$method();
            }

            //route for the AddNew button
            $this->dto['addNewButtonRoute'] = 'event.create';

            //header for select dashboards
            $this->dto['dashboardHeader'] = $this->getDashboardHeader();

        } else { //user default values

            $this->dto['pageInstructions'] = '';
            $this->dto['pageName'] = 'pages.dashboardPage';
            $this->dto['header'] = 'unknown';
            $this->dto['cards'] = [];
        }

    }

    /**
     * Decode instructions that were created through the FilamentPHP Rich Text object
     * @param $encoded
     * @return string
     */
    private function decodeInstructions($encoded): string
    {
        return html_entity_decode($encoded);
    }

    private function getColumnHeaders(): array
    {
        $headers = [];

        return array_key_exists($this->viewPage->header, $headers) ? $headers[$this->viewPage->header] : [];
    }

    private function getComponents(): array
    {
        $components = [
            'dashboard' => ['cards'],
            'livewire' => ['livewireComponent'],
            'table' => ['columnHeaders', 'rows'],
        ];

        return $components[$this->viewPage->page_name];
    }

    private function getDashboardHeader(): string
    {
        $versionName = Version::find($this->dto['id'])->name ?? '';

        $dashboardHeaders = [
            'version dashboard' => $versionName,
        ];

        return $dashboardHeaders[$this->dto['header']] ?? '';
    }

    public function getDto(): array
    {
        return $this->dto;
    }

    /**
     * Static cards are stored in the database and accessed by query, otherwise
     * Cards are created dynamically via Data/Cards/<$this->dto['header'].Card file ex: Data/Cards/SchoolsCard
     *
     * @return array
     */
    private function getCards(): array
    {
        $default = []; //no cards

        if (ViewCard::query()
            ->where('header', $this->dto['header'])
            ->exists()) {

            return ViewCard::query()
                ->where('header', $this->dto['header'])
                ->orderBy('order_by')
                ->get()
                ->toArray();
        }

        return $default;
    }

    private function getLivewireComponent(): string
    {
        $components = [
            'candidates' => 'events.versions.participations.candidates-table-component',

            'new school' => 'schools.school-create-component',
            'schools' => 'schools.schools-table-component',
            'school edit' => 'schools.school-edit-component',

            'new student' => 'students.student-create-component',
            'students' => 'students.students-table-component',
            'student edit' => 'students.student-edit-component',
            'student comms edit' => 'students.student-comms-edit-component',
            'student ec edit' => 'students.student-e-c-edit-component',
            'student reset password' => 'students.student-reset-password-component',

            'ensembles' => 'ensembles.ensembles-table-component',
            'ensemble create' => 'ensembles.ensemble-create-component',
            'ensemble edit' => 'ensembles.ensemble-edit-component',

            'events participation' => 'events.event-participation-table-component',

            'assets' => 'ensembles.assets.assets-table-component',
            'asset create' => 'ensembles.assets.asset-create-component',
            'asset edit' => 'ensembles.assets.asset-edit-component',

            'inventories' => 'ensembles.inventories.inventories-table-component',
            'inventory create' => 'ensembles.inventories.inventory-create-component',
            'inventory edit' => 'ensembles.inventories.inventory-edit-component',

            'members' => 'ensembles.members.members-table-component',
            'member create' => 'ensembles.members.member-create-component',
            'member edit' => 'ensembles.members.member-edit-component',

            'event edit' => 'events.event-edit-component',
            'my events' => 'events.events-table-component',
            'new event' => 'events.event-create-component',

            'version configs edit' => 'events.versions.version-configs-edit-component',
            'version dates edit' => 'events.versions.version-dates-edit-component',
            'version edit' => 'events.versions.version-edit-component',
            'version participants' => 'events.versions.version-participants-table-component',
            'version pitch files' => 'events.versions.version-pitch-files-table-component',
            'version profile' => 'events.versions.version-profile-component',
            'version roles' => 'events.versions.version-role-component',
            'version scoring' => 'events.versions.version-scoring-table-component',
            'version edit profile' => 'events.versions.version-profile-component',

            'versions' => 'events.versions.versions-table-component',
            'versions table' => 'events.versions.versions-table-component',

        ];

        return $components[$this->viewPage->header];
    }

    private function getRows(): array
    {
        $rows = [];

        return array_key_exists($this->dto['header'], $rows) ? $rows[$this->dto['header']] : [];
    }
}
