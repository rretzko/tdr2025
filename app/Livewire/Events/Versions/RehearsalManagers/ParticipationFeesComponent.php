<?php

namespace App\Livewire\Events\Versions\RehearsalManagers;

use App\Exports\ParticipationFeesExport;
use App\Livewire\BasePage;
use App\Models\UserConfig;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ParticipationFeesComponent extends BasePage
{
    public int $versionId;

    public function mount(): void
    {
        $this->versionId = UserConfig::getValue('versionId');
    }

    public function render()
    {
        return view('livewire..events.versions.rehearsal-managers.participation-fees-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    private function getRows(): array
    {
        return DB::table('epayments')
            ->join('users', 'epayments.user_id', '=', 'users.id')
            ->join('candidates', 'epayments.candidate_id', '=', 'candidates.id')
            ->join('schools', 'candidates.school_id', '=', 'schools.id')
            ->where('epayments.version_id', $this->versionId)
            ->where('fee_type', 'participation')
            ->select('users.name', 'schools.name AS schoolName',
                DB::raw('epayments.amount * .01 AS amount'),
                'epayments.transaction_id AS transactionId',
                'epayments.comments',
                DB::raw("DATE_FORMAT(epayments.created_at, '%b %d, %Y %h:%i:%s') AS createdAt")
            )
            ->get()
            ->toArray();
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //clear any artifacts
        $this->reset('search');

        $rows = $this->getRows();

        $fileName = 'participationFees_'.date('Ymd_His').'.csv';

        return Excel::download(new ParticipationFeesExport($rows), $fileName);
    }
}
