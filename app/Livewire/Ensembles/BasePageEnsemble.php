<?php

namespace App\Livewire\Ensembles;

use App\Livewire\BasePage;
use App\Livewire\Forms\EnsembleForm;
use App\Models\Ensembles\Asset;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Library;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use Illuminate\Support\Collection;


class BasePageEnsemble extends BasePage
{
    public Collection $assets;
    public array $ensembleAssets = [];
    public Ensemble $ensemble;
    public EnsembleForm $form;
    public array $gradesITeaches = [];

    public function mount(): void
    {
        parent::mount();

        if ($this->school->id) {
            $this->form->setSchool($this->school);
        }

        $this->ensemble = ($this->dto['id'])
            ? Ensemble::find($this->dto['id'])
            : new Ensemble;

        if ($this->ensemble->id) {

            $this->form->setEnsemble($this->ensemble);
        }

        $this->assets = Asset::query()
            ->whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $this->gradesITeaches = $this->getGradesITeachArray();

    }

    private function getGradesITeachArray(): array
    {
        $schoolId = UserConfig::getValue('schoolId');
        $teacherId = Teacher::where('user_id', auth()->id())->pluck('id');

        return GradesITeach::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', $teacherId)
            ->orderBy('grade')
            ->pluck('grade')
            ->toArray();
    }
}
