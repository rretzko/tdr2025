<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Exports\EventEnsembleParticipantsExport;
use App\Exports\EventEnsembleSeniorityParticipationExport;
use App\Livewire\BasePage;
use App\Models\Events\Event;
use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use App\Services\ScoringRosterDataRowsService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;

class TabroomReportComponent extends BasePage
{
    public array $categories = [];
    public string $displayReportData = '';
    public bool $displayReport = false;
    public string $eventEnsembleAbbr = '';
    public int $eventEnsembleCount = 0;
    public int $eventEnsembleId = 0;
    public Collection $eventEnsembles;
    public array $eventEnsembleSeniorYears = [];
    public Collection $factors;
    public int $judgeCount;
    public int $rowLimit = 0;
    public array $rowsScores = [];
    public bool $scoresAscending = true;
    public array $seniorityParticipation = [];
    public int $showEventEnsembleId = 0;
    public bool $showPrinterIcon = false;
    public int $versionId = 0;
    public array $versionSeniorYears = [];
    public int $voicePartId = 0;
    public array $voicePartIds = [];
    public Collection $voiceParts;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->judgeCount = $versionConfigAdjudication->judge_per_room_count;
        $this->scoresAscending = $versionConfigAdjudication->scores_ascending;
        $this->categories = $this->getCategories();
        $this->factors = $this->getFactorAbbrs();
        $this->voiceParts = $this->getVoiceParts();
        $this->voicePartIds = $this->voiceParts->pluck('id')->toArray();
        $this->voicePartId = $this->voicePartIds[0];
//
        $this->eventEnsembles = Version::find($this->versionId)->event->eventEnsembles;
        $this->eventEnsembleCount = $this->eventEnsembles->count();
        $this->eventEnsembleId = $this->eventEnsembles->first()->id;
        $this->showEventEnsembleId = $this->eventEnsembleId;
        if ($this->eventEnsembleId) {
            $this->eventEnsembleAbbr = EventEnsemble::find($this->eventEnsembleId)->abbr;
        }

        $this->showPrinterIcon = config('app.env') === 'local';

    }

    public function render()
    {

        return view('livewire..events.versions.tabrooms.tabroom-report-component',
            [
                'rows' => $this->getRows(),
                'participants' => $this->getParticipants(),
                'eventEnsembleYears' => $this->getEventEnsembleYears(),
            ]);
    }

    #[NoReturn] public function clickButton(string $type): void
    {
        $this->reset('voicePartId', 'rowLimit');

        if ($type === 'byVoicePart') {
            $this->voicePartId = $this->voiceParts->first()->id;
        }

        if ($type === 'allPrivate') {
            $this->rowLimit = 1000;
        }

        if ($type === 'seniorityParticipation') {
            $this->versionSeniorYears = $this->getVersionSeniorYears();
            $this->eventEnsembleSeniorYears = $this->getEventEnsembleYears();
            $this->seniorityParticipation = $this->getParticipantsForSeniority(); //$this->getSeniorityParticipation();
        }

        $this->displayReport = !$this->displayReport;

        $this->displayReportData = $type;
    }

    #[NoReturn] public function clickPrinter()
    {
        $report = ($this->displayReportData == 'allPublic')
            ? 'byVoicePart'
            : $this->displayReportData;

        $uri = '/versions/tabroom/reports/'.$report;

        //add voice part designation
        $uri .= ($this->voicePartId)
            ? '/'.$this->voicePartId
            : '/'. 74; //all voices

        //add private/public designation
        $uri .= ($this->displayReportData === 'allPrivate')
            ? '/1'  //1=private
            : '/0'; //0=public

        //add eventEnsembleId
        $uri .= '/'.$this->showEventEnsembleId;
        //ex: /versions/tabroom/reports/allPrivate/74/1/7
        //ex: /versions/tabroom/reports/byVoicePart/74/0/0

        return $this->redirect($uri);
    }

    /**
     * Export participants csv file
     */
    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //clear any artifacts
        $this->reset('search');

        $abbr = 'ALL_';
        if ($this->eventEnsembleId) {
            $abbr = EventEnsemble::find($this->eventEnsembleId)->abbr.'_';
        }

        $fileName = $abbr.'ensembleParticipants_'.Carbon::now()->format('Ymd_His').'.csv';

        return Excel::download(new EventEnsembleParticipantsExport($this->getParticipants()), $fileName);
    }

    /**
     * Export senior and junior grade participants csv file
     * demonstrating participation in past events
     */
    public function exportSeniority(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $fileName = 'seniority_'.Carbon::now()->format('Ymd_His').'.csv';

        return Excel::download(new EventEnsembleSeniorityParticipationExport($this->getParticipantsForSeniority()),
            $fileName);
    }

    public function updatedShowEventEnsembleId(int $value): void
    {
        if (!$value) {
            $this->reset('eventEnsembleAbbr', 'eventEnsembleId');
        } else {
            $this->eventEnsembleId = $value;
            $eventEnsemble = EventEnsemble::find($this->eventEnsembleId);
            $this->eventEnsembleAbbr = $eventEnsemble->abbr;
        }
    }

    public function updatedEventEnsembleId(): void
    {
        $this->showEventEnsembleId = $this->eventEnsembleId;
        $this->seniorityParticipation = $this->getParticipantsForSeniority();
        $this->eventEnsembleSeniorYears = $this->getEventEnsembleYears();
    }

//    public function updatedShowEventEnsembleId(): void
//    {
//        //rerender
//    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function addParticipationYears(array &$participants)
    {
        $version = Version::find($this->versionId);
        $event = $version->event;
        $versions = $event->versions->sortByDesc('senior_class_of');

        $eventEnsembleAbbrs = $event->eventEnsembles->pluck('abbr')->toArray();

        foreach ($participants as $participant) {

            $userId = $participant->userId;
            $participant->countYears = 0;

            foreach ($versions as $key => $version) {

                $successful = DB::table('candidates')
                    ->leftJoin('audition_results', function ($join) use ($eventEnsembleAbbrs) {
                        $join->on('candidates.id', '=', 'audition_results.candidate_id')
                            ->where('audition_results.accepted', 1)
                            ->whereIn('audition_results.acceptance_abbr', $eventEnsembleAbbrs);
                    })
                    ->where('candidates.version_id', $version->id)
                    ->where('candidates.student_id', $userId)
                    ->where('candidates.status', 'registered')
                    ->get();

                if ($successful->isNotEmpty()) {
                    $participant->countYears++;
                    $participant->years[] = '*';
                } else {
                    $participant->years[] = '';
                }
            }

        }
    }

    private function calcTopTwoClassOfs(EventEnsemble $eventEnsemble): array
    {
        $eventEnsembleGrades = explode(',', $eventEnsemble->grades);
        $topGrade = max($eventEnsembleGrades);

        $service = new CalcSeniorYearService();
        $seniorYear = $service->getSeniorYear();

        $topYear = $seniorYear + (12 - $topGrade);
        $nextYear = ($topYear + 1);

        return [$topYear, $nextYear];
    }

    private function getCategories(): array
    {
        $version = Version::find($this->versionId);

        return $version->scoreCategoriesWithColSpanArray;
    }

    private function getFactorAbbrs(): Collection
    {
        $version = Version::find($this->versionId);

        return $version->scoreFactors;
    }

    private function getParticipants(): array
    {
        $ensemble = EventEnsemble::find($this->eventEnsembleId);
        $abbr = $ensemble->abbr ?? '';
        $versionId = $this->versionId;
        $scoresAscending = Version::find($this->versionId)->scores_ascending;
        $voicePartsIn = $this->getShowEnsembleVoicePartIds();

        $participants = DB::table('audition_results')
            ->join('candidates', 'audition_results.candidate_id', '=', 'candidates.id')
            ->join('voice_parts', 'audition_results.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'audition_results.school_id', '=', 'schools.id')
            ->join('students', 'candidates.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('users AS usersT', 'candidates.teacher_id', '=', 'usersT.id')
            ->leftJoin('emergency_contacts', 'candidates.emergency_contact_id', '=', 'emergency_contacts.id')
            //STUDENT
            ->leftJoin('phone_numbers AS homePhone', function ($join) {
                $join->on('users.id', '=', 'homePhone.user_id')
                    ->where('homePhone.phone_type', 'home');
            })
            ->leftJoin('phone_numbers AS mobilePhone', function ($join) {
                $join->on('users.id', '=', 'mobilePhone.user_id')
                    ->where('mobilePhone.phone_type', 'mobile');
            })
            //TEACHER
            ->leftJoin('phone_numbers AS mobilePhoneT', function ($join) {
                $join->on('usersT.id', '=', 'mobilePhoneT.user_id')
                    ->where('mobilePhoneT.phone_type', 'mobile');
            })
            ->leftJoin('phone_numbers AS workPhoneT', function ($join) {
                $join->on('usersT.id', '=', 'workPhoneT.user_id')
                    ->where('workPhoneT.phone_type', 'work');
            })
            ->whereIn('candidates.voice_part_id', $voicePartsIn)
            ->where('audition_results.version_id', $versionId)
            ->where('acceptance_abbr', $abbr)
            ->where('accepted', 1)
            ->distinct()
            ->select('candidates.program_name AS programName',
                'users.last_name',
                'schools.name AS schoolName',
                'usersT.name AS teacherName',
                'voice_parts.abbr AS voicePartAbbr',
                'voice_parts.order_by',
                'audition_results.total',
                'users.email',
                'mobilePhone.phone_number AS phoneMobile',
                'homePhone.phone_number AS phoneHome',
                'usersT.email as teacherEmail',
                'mobilePhoneT.phone_number AS phoneMobileT',
                'workPhoneT.phone_number AS phoneWorkT',
                'emergency_contacts.name AS EcName',
                'emergency_contacts.email AS EcEmail',
                'emergency_contacts.phone_mobile AS phoneMobileEC',
                'emergency_contacts.phone_home AS phoneHomeEC',
                'emergency_contacts.phone_work AS phoneWorkEC',
                'students.id AS studentId'
            )
            ->orderBy('voice_parts.order_by')
            ->orderBy('audition_results.total', ($scoresAscending ? 'asc' : 'desc'))
            ->get()
            ->toArray();
Log::info('participants: ' . count($participants));
        return $participants;
    }

    private function getEventEnsembleYears(): array
    {
        $grades = ($this->eventEnsembleId)
            ? explode(',', EventEnsemble::find($this->eventEnsembleId)->grades)
            : explode(',', Version::find($this->versionId)->event->grades);

        $maxGrade = max($grades);
        arsort($grades);

        $service = new CalcSeniorYearService();
        $currentYear = $service->getSeniorYear();

        $ensembleYears = [];
        foreach ($grades as $grade) {
            $ensembleYears[] = ($currentYear - ($maxGrade - $grade));
        }

        return $ensembleYears;
    }

    private function getParticipantsForSeniority(): array
    {
        $ensemble = EventEnsemble::find($this->eventEnsembleId);
        $abbr = $ensemble->abbr ?? '';
        $versionId = $this->versionId;

        //return participants from the most-senior top two class-ofs
        $topTwoClassOfs = $this->calcTopTwoClassOfs($ensemble);

        $participants = DB::table('audition_results')
            ->join('candidates', 'audition_results.candidate_id', '=', 'candidates.id')
            ->join('voice_parts', 'audition_results.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'audition_results.school_id', '=', 'schools.id')
            ->join('students', 'candidates.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('users AS usersT', 'candidates.teacher_id', '=', 'usersT.id')
            ->whereIn('students.class_of', $topTwoClassOfs)
            ->where('audition_results.version_id', $versionId)
            ->where('acceptance_abbr', $abbr)
            ->where('accepted', 1)
            ->distinct()
            ->select('candidates.program_name AS programName',
                'users.last_name',
                'students.class_of',
                'users.id AS userId',
                'schools.name AS schoolName',
                'usersT.name AS teacherName',
                'voice_parts.abbr AS voicePartAbbr',
            )
            ->orderBy('users.last_name')
            ->get()
            ->toArray();

        $this->addParticipationYears($participants);

        usort($participants, function ($a, $b) {
            return $b->countYears <=> $a->countYears;
        });

        return $participants;
    }

    private function getRows(): array
    {
        $voicePartIds = $this->voicePartId ? [$this->voicePartId] : $this->getShowEnsembleVoicePartIds(); //voicePartIds;

        $service = new ScoringRosterDataRowsService(
            $this->versionId,
            $voicePartIds,
            $this->showEventEnsembleId,
            $this->judgeCount,
            $this->scoresAscending,
            $this->factors->count(),
            $this->voicePartId,
            $this->eventEnsembleAbbr,
            $this->rowLimit,
            $this->displayReportData,
        );

        $this->rowsScores = $service->getRowsScores();

        return $service->getRows();
    }

//    private function getScores(array &$candidates): void
//    {
//        foreach ($candidates as $candidate) {
//
//            for ($i = 1; $i <= $this->judgeCount; $i++) {
//
//                foreach ($this->factors as $factor) {
//
//                    $candidate->scores[] = Score::query()
//                        ->where('candidate_id', $candidate->id)
//                        ->where('judge_order_by', $i)
//                        ->where('score_factor_order_by', $factor->order_by)
//                        ->select('score')
//                        ->value('score') ?? 0;
//                }
//            }
//        }
//    }

    private function getShowEnsembleVoicePartIds(): array
    {
        $ensembles = $this->eventEnsembles;
        if ($this->showEventEnsembleId) {
            $ensembles = $this->eventEnsembles->where('id', $this->showEventEnsembleId);
        }

        $voicePartIds = [];
        foreach ($ensembles as $ensemble) {
            $idsArray = explode(',', $ensemble->voice_part_ids);
            $voicePartIds = array_merge($voicePartIds, $idsArray);
        }


        return $voicePartIds;
    }

    private function getVersionSeniorYears(): array
    {
        $version = Version::find($this->versionId);
        $event = $version->event;
        $seniorYear = $version->senior_class_of;

        //gradeCount tells you how many years a single participant can be active
        $gradeCount = count($event->gradesArray);

        $versionSeniorYears = [];
        for ($i = 0; $i < $gradeCount; $i++) {
            $versionSeniorYears[] = ($seniorYear - $i);
        }

        return $versionSeniorYears;
    }


    private function getVoiceParts(): Collection
    {
        $versionId = UserConfig::getValue('versionId');
        return Version::find($versionId)->event->voiceParts;
    }
}
