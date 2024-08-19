<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\UserConfig;
use App\Services\CoTeachersService;
use App\Services\EventEnsemblesVoicePartsArrayService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ResultsTableComponent extends BasePage
{
    public array $ensembleVoiceParts = [];
    public Event $event;
    public bool $hasContract = false;
    public int $schoolId = 0;
    public bool $showAllScores = false;
    public Version $version;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->schoolId = UserConfig::getValue('schoolId');
        $this->versionId = $this->dto['id'];
        $this->version = Version::find($this->versionId);
        $this->event = $this->version->event;

        $this->sortCol = 'users.last_name';

        //requires $this->event to be initialized;
        $this->ensembleVoiceParts = $this->getEnsembleVoiceParts();

        //successful auditionees has participation contracts in these events
        $eventsWithContracts = [19, 25];
        $this->hasContract = in_array($this->event->id, $eventsWithContracts);

        //should user see all-scores pdf?
        $vca = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->showAllScores = $vca->show_all_scores;

    }

    private function getEnsembleVoiceParts(): array
    {
        $service = new EventEnsemblesVoicePartsArrayService($this->event->eventEnsembles);

        return $service->getArray();
    }

    public function render()
    {
        return view('livewire..events.versions.participations.results-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]
        );
    }

    private function getColumnHeaders(): array
    {
        $a = [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'],
            ['label' => 'voice part', 'sortBy' => null],
            ['label' => 'score', 'sortBy' => null],
            ['label' => 'accepted', 'sortBy' => null],
            ['label' => 'ensemble', 'sortBy' => null],
        ];

        if ($this->hasContract) {
            $a[] = ['label' => 'contract', 'sortBy' => null];
        }

        return $a;
    }

    private function getRows(): Builder
    {
        // $this->test();
        $coTeacherIds = CoTeachersService::getCoTeachersIds();

        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS tusers', 'tusers.id', '=', 'teachers.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->where('candidates.school_id', $this->schoolId)
            ->where('status', 'registered')
            ->tap(function ($query) {
                $this->filters->filterCandidatesByClassOfs($query);
                $this->filters->filterCandidatesByStatuses($query, $this->search);
            })
            ->select('candidates.id AS candidateId', 'candidates.ref', 'candidates.status',
                'candidates.program_name',
                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'students.class_of',
                'voice_parts.abbr AS voicePartAbbr',
                'audition_results.total', 'audition_results.accepted', 'audition_results.acceptance_abbr'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name', 'asc') //secondary sort ALWAYS applied
            ->orderBy('users.first_name', 'asc'); //tertiary sort ALWAYS applied
    }

    public function printContract(int $candidateId): \Livewire\Features\SupportRedirects\Redirector
    {
        return redirect()->to('pdf/contract/'.$candidateId);
    }

    public function printResult(int $candidateId): \Livewire\Features\SupportRedirects\Redirector
    {
        return redirect()->to('pdf/candidateScore/'.$candidateId);
    }

    public function printResultsAll(): \Livewire\Features\SupportRedirects\Redirector
    {
        return redirect()->to('pdf/candidateScoresSchool/');
    }

    public function printResultsConfidential()//: \Livewire\Features\SupportRedirects\Redirector
    {
        $versionId = UserConfig::find('versionId');
        $fileName = "combinedConfidentialPdfs/combinedConfidential_{$versionId}.pdf";
        $disk = Storage::disk('s3');

        if ($disk->exists($fileName)) {

            return new StreamedResponse(function () use ($disk, $fileName) {
                $stream = $disk->readStream($fileName);
                fpassthru($stream);
                fclose($stream);
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="auditionResults.pdf"',
            ]);
        }

        return redirect()->to('pdf/candidateScoresSchool/');
    }

    private function test(): void
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();
        dd(DB::table('candidates')
//            ->join('students', 'students.id', '=', 'candidates.student_id')
//            ->join('users', 'users.id', '=', 'students.user_id')
//            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
//            ->join('users AS tusers', 'tusers.id', '=', 'teachers.user_id')
//            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->where('candidates.school_id', $this->schoolId)
            ->where('status', 'registered')
            ->tap(function ($query) {
                $this->filters->filterCandidatesByClassOfs($query);
                $this->filters->filterCandidatesByStatuses($query, $this->search);
            })
            ->get());
//            ->select('candidates.id AS candidateId', 'candidates.ref', 'candidates.status',
//                'candidates.program_name',
//                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
//                'students.class_of',
//                'voice_parts.abbr AS voicePart'
//            )
//            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
//            ->orderBy('users.last_name', 'asc') //secondary sort ALWAYS applied
//            ->orderBy('users.first_name', 'asc'); //tertiary sort ALWAYS applied);
    }
}
