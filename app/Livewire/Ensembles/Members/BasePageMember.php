<?php

namespace App\Livewire\Ensembles\Members;

use App\Livewire\BasePage;
use App\Livewire\Forms\SchoolEnsembleMemberForm;
use App\Models\Ensembles\Asset;
use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Students\VoicePart;
use App\Services\CalcGradeFromClassOfService;
use App\Services\CalcSeniorYearService;
use JetBrains\PhpStorm\NoReturn;

class BasePageMember extends BasePage
{
    const OFFICES = [
        'member' => 'member',
        'president' => 'president',
        'vice-president' => 'vice-president',
        'treasurer' => 'treasurer',
        'secretary' => 'secretary',
        'librarian' => 'librarian',
        'co-president' => 'co-president',
        'co-vice-president' => 'co-vice-president',
        'co-treasurer' => 'co-treasurer',
        'co-secretary' => 'co-secretary',
        'other' => 'other',
    ];

    const STATUSES = [
        'active', 'inactive', 'probationary', 'conditional', 'removed', 'withdrew', 'other'
    ];
    public SchoolEnsembleMemberForm $form;
    public string $selectedTab = 'members';

    protected function getAssets(): array
    {
        //early exit
        if ($this->form->sysId === 'new') {
            return [];
        }

        return AssetEnsemble::query()
            ->join('assets', 'assets.id', '=', 'asset_ensemble.asset_id')
            ->where('ensemble_id', $this->form->ensembleId)
            ->select('assets.name', 'assets.id AS assetId')
            ->orderBy('assets.name')
            ->get()
            ->toArray();
    }

    protected function getVoiceParts(): array
    {
        return VoicePart::query()
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }

    public function updatedFormClassOfGrade(): void
    {
        ($this->form->classOfGrade < 13)
            ? $this->setFormClassOfFromGrade()
            : $this->setFormGradeFromClassOf();
    }

    protected function setFormClassOfFromGrade(): void
    {
        $service = new CalcSeniorYearService();
        $srYear = $service->getSeniorYear();

        $this->form->grade = $this->form->classOfGrade;

        $this->form->classOf = ($srYear + (12 - $this->form->grade));
    }

    protected function setFormGradeFromClassOf(): void
    {
        $this->form->classOf = $this->form->classOfGrade;

        $service = new CalcGradeFromClassOfService();
        $this->form->grade = $service->getGrade($this->form->classOf);
    }


}
