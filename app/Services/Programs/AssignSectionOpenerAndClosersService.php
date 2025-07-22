<?php

namespace App\Services\Programs;

use App\Models\Programs\Program;
use App\Models\Programs\ProgramSelection;

readonly class AssignSectionOpenerAndClosersService
{
    private bool $isEnsemble;

    public function __construct(private int $programId)
    {
        $program = Program::findOrFail($this->programId);
        $this->isEnsemble = $program->organized_by === 'ensemble';
        $this->init();
    }

    private function init(): void
    {
        //reset all selections opener/closer to default 0
        ProgramSelection::query()
            ->where('program_id', $this->programId)
            ->update(['opener' => 0, 'closer' => 0]);

        if ($this->isEnsemble) {
            $this->initEnsembles();
        } else {
            $this->initActs();
        }
    }

    private function initActs(): void
    {

        //identify actIds for the current program
        $actIds = ProgramSelection::query()
            ->where('program_id', $this->programId)
            ->distinct()
            ->pluck('act_id')
            ->toArray();

        //set the min(order_by) opener value to 1 for each actId
        //set the max(order_by) closer value to 1 for each actId
        foreach ($actIds as $actId) {

            // Get the minimum order_by for this act
            $minOrderBy = ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('act_id', $actId)
                ->min('order_by');

            // Update the row(s) with the minimum order_by to set opener = 1
            ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('act_id', $actId)
                ->where('order_by', $minOrderBy)
                ->update(['opener' => 1]);

            // Get the maximum order_by for this act
            $maxOrderBy = ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('act_id', $actId)
                ->max('order_by');

            // Update the row(s) with the maximum order_by to set opener = 1
            ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('act_id', $actId)
                ->where('order_by', $maxOrderBy)
                ->update(['closer' => 1]);
        }
    }

    private function initEnsembles(): void
    {
        //identify ensembleIds for the current program
        $ensembleIds = ProgramSelection::query()
            ->where('program_id', $this->programId)
            ->distinct()
            ->pluck('ensemble_id')
            ->toArray();

        //set the min(order_by) opener value to 1 for each ensembleId
        //set the max(order_by) closer value to 1 for each ensembleId
        foreach ($ensembleIds as $ensembleId) {

            // Get the minimum order_by for this ensemble
            $minOrderBy = ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('ensemble_id', $ensembleId)
                ->min('order_by');

            // Update the row(s) with the minimum order_by to set opener = 1
            ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('ensemble_id', $ensembleId)
                ->where('order_by', $minOrderBy)
                ->update(['opener' => 1]);

            // Get the maximum order_by for this ensemble
            $maxOrderBy = ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('ensemble_id', $ensembleId)
                ->max('order_by');

            // Update the row(s) with the maximum order_by to set opener = 1
            ProgramSelection::query()
                ->where('program_id', $this->programId)
                ->where('ensemble_id', $ensembleId)
                ->where('order_by', $maxOrderBy)
                ->update(['closer' => 1]);
        }
    }

}
