<?php

namespace App\Data;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use App\Models\PageInstruction;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\ViewCard;
use App\Services\VersionsTableService;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;

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

        //common variable
        $this->dto['versionId'] = $this->versionId;

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

    private function filterCards(array $viewCards): array
    {
        $cards = $this->filterCardsByRole($viewCards);

        if ($this->dto['header'] === 'home') {
            $cards = $this->filterHomeCards($cards);
        }

        if ($this->versionId) {

            $cards = $this->filterCardsByAdjudicationFactors($cards);
        }

        return $cards;
    }

    private function filterCardsByAdjudicationFactors(array $viewCards): array
    {
        $cards = $viewCards;

        $versionConfigDateOpen = VersionConfigDate::where('version_id', $this->versionId)
            ->where('date_type', 'adjudication_open')
            ->first();

        if ($versionConfigDateOpen) {
            $adjudicationOpen = Carbon::parse($versionConfigDateOpen->version_date);
        } else {
            $adjudicationOpen = null; // or handle it as needed
        }

        $versionConfigDateClose = VersionConfigDate::where('version_id', $this->versionId)
            ->where('date_type', 'adjudication_close')
            ->first();

        if ($versionConfigDateClose) {
            $adjudicationClose = Carbon::parse($versionConfigDateClose->version_date);
        } else {
            $adjudicationClose = null; // or handle it as needed
        }

        $now = Carbon::now();

        $isJudge = $this->isJudge();

        if (!($adjudicationOpen && $adjudicationClose && ($adjudicationOpen < $now) && ($adjudicationClose > $now) && $isJudge)) {
            $cards = array_filter($cards, function ($card) {
                return ($card['label'] !== 'adjudication');
            });
        }

        return $cards;
    }

    /**
     * Apply filters on roles held by user
     * @return array
     */
    #[NoReturn]
    private function filterCardsByRole(array $cards): array
    {
        //early exit
        if ($this->dto['header'] !== 'version dashboard') {
            return $cards;
        }

        $versionRoles = $this->getVersionRoles();

        //founder and version's event manager(s) have access to all cards
        if (auth()->user()->isFounder() || $versionRoles->contains('event manager')) {
            return $cards;
        }

        //filter for coRegistration manager
        if ($versionRoles->contains('coregistration manager')) {
            return $this->filterCardsForRegistrationManager($cards);
        }

        //filter for online registration manager
        if ($versionRoles->contains('online registration manager')) {
            return $this->filterCardsForOnlineRegistrationManager($cards);
        }

        //filter for registration manager
        if ($versionRoles->contains('registration manager')) {
            return $this->filterCardsForRegistrationManager($cards);
        }

        //filter for tab room
        if ($versionRoles->contains('online registration manager')) {
            return $this->filterCardsForTabRoom($cards);
        }

        return [];
    }

    /**
     * @param  array  $cards
     * @return array
     */
    private function filterCardsForCoRegistrationManager(array $cards): array
    {
        return array_filter($cards, function ($card) {
            return ($card['role'] === 'coregistration manager');
        });
    }

    /**
     * @param  array  $cards
     * @return array
     */
    private function filterCardsForOnlineRegistrationManager(array $cards): array
    {
        return array_filter($cards, function ($card) {
            return ($card['role'] === 'online registration manager');
        });
    }

    /**
     * @param  array  $cards
     * @return array
     */
    private function filterCardsForRegistrationManager(array $cards): array
    {
        return array_filter($cards, function ($card) {
            return ($card['role'] === 'registration manager');
        });
    }

    /**
     * @param  array  $cards
     * @return array
     */
    private function filterCardsForTabRoom(array $cards): array
    {
        return array_filter($cards, function ($card) {
            return ($card['role'] === 'tab room');
        });
    }

    private function filterHomeCards(array $cards): array
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schoolId = UserConfig::getValue('schoolId');
        $school = School::find($schoolId);
        //array
        $grades = $school->grades;
        //Collection
        $gradesITeach = GradesITeach::where('school_id', $schoolId)->where('teacher_id', $teacher->id)->get();
        //bool
        $hasAllGrades = count($grades) && $gradesITeach->count();

        $suppressedCards = ['ensembles', 'events', 'libraries', 'students',];
        if (!$hasAllGrades) {
            foreach ($cards as $key => $card) {
                if (in_array($card['label'], $suppressedCards)) {
                    unset($cards[$key]);
                }
            }
        }

        return $cards;
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
        $viewCards = ViewCard::query()
            ->where('header', $this->dto['header'])
            ->orderBy('order_by')
            ->get();

        return ($viewCards->isNotEmpty())
            ? $this->filterCards($viewCards->toArray())
            : [];
    }

    private function getLivewireComponent(): string
    {
        $components = [
            'adjudication paper backup' => 'events.versions.reports.adjudication-paper-backup-component',
            'adjudication csv backup' => 'events.versions.reports.adjudication-csv-backup-component',
            'adjudication monitor checklist' => 'events.versions.reports.adjudication-monitor-checklist-component',

            'attachments' => 'events.versions.attachments.attachment-component',

            'candidates' => 'events.versions.participations.candidates-table-component',
            'candidates recordings' => 'events.versions.participations.candidates-recordings-table-component',
            'candidates table' => 'events.versions.participations.candidates-table-component',

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

            'participation results' => 'events.versions.participations.results-table-component',

            'assets' => 'ensembles.assets.assets-table-component',
            'asset create' => 'ensembles.assets.asset-create-component',
            'asset edit' => 'ensembles.assets.asset-edit-component',

            'inventories' => 'ensembles.inventories.inventories-table-component',
            'inventory new' => 'ensembles.inventories.inventory-create-component',
            'inventory edit' => 'ensembles.inventories.inventory-edit-component',
            'inventory' => 'ensembles.inventories.inventory.inventories-table-component',
            'inventory mass add' => 'ensembles.inventories.inventory-mass-add-component',
            'assign assets' => 'ensembles.inventories.assign-asset-component',

            'judge assignment' => 'events.versions.judge-assignment-component',

            'libraries' => 'libraries.libraries-table-component',

            'members' => 'ensembles.members.members-table-component',
            'member create' => 'ensembles.members.member-create-component',
            'member edit' => 'ensembles.members.member-edit-component',
            'member mass add' => 'ensembles.members.member-mass-add-component',

            'estimate' => 'events.versions.participations.estimate-component',
            'obligations' => 'events.versions.participations.obligations-component',
            'pitchfiles' => 'events.versions.participations.pitch-files-component',
            'event edit' => 'events.event-edit-component',
            'my events' => 'events.events-table-component',
            'new event' => 'events.event-create-component',

            'student transfer' => 'events.versions.version-student-transfer-component',

            'timeslot assignment' => 'events.versions.timeslot-assignment-component',

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

            //version reports
            'obligated teachers' => 'events.versions.reports.obligated-teachers-component',
            'participating schools' => 'events.versions.reports.participating-schools-component',
            'participating students' => 'events.versions.reports.participating-students-component',
            'participating teachers' => 'events.versions.reports.participating-teachers-component',
            'registration cards' => 'events.versions.reports.registration-cards-component',
            'student counts' => 'events.versions.reports.student-counts-component',

            //pitch files
            'teacher pitch files' => 'events.versions.participations.teacher-pitch-files-table-component',

            //adjudication
            'adjudication' => 'events.versions.adjudications.adjudication-component',

            //rehearsal manager
            'participation fees' => 'events.versions.rehearsalManagers.participation-fees-component',

            //tab room
            'tabroom cutoff' => 'events.versions.tabrooms.tabroom-cutoff-component',
            'tabroom reports' => 'events.versions.tabrooms.tabroom-report-component',
            'tabroom scoring' => 'events.versions.tabrooms.tabroom-scoring-component',
            'tabroom tracking' => 'events.versions.tabrooms.tabroom-tracking-component',
            'tabroom close auditions' => 'events.versions.tabrooms.tabroom-close-auditions-component',

            //student dossier
            'student dossier' => 'students.student-dossier-component',

        ];

        return $components[$this->viewPage->header];
    }

    private function getRows(): array
    {
        $rows = [];

        return array_key_exists($this->dto['header'], $rows) ? $rows[$this->dto['header']] : [];
    }

    private function getVersionRoles(): Collection
    {
        $versionId = UserConfig::getValue('versionId');

        $engageds = ['invited', 'obligated', 'participating'];

        $versionParticipantId = VersionParticipant::query()
            ->where('user_id', auth()->id())
            ->where('version_id', $versionId)
            ->whereIn('status', $engageds)
            ->value('id');

        return VersionRole::query()
            ->where('version_participant_id', $versionParticipantId)
            ->where('version_id', $versionId)
            ->distinct('role')
            ->pluck('role');
    }

    private function isJudge(): bool
    {
        return Judge::query()
            ->where('version_id', $this->versionId)
            ->where('user_id', auth()->id())
            ->where('judge_type', '!=', 'monitor') //includes head judge, judge 2, judge 3, judge 3, judge monitor
            ->exists();
    }
}
