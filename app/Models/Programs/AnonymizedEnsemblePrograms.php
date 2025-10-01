<?php

namespace App\Models\Programs;

use App\Models\Ensembles\Ensemble;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AnonymizedEnsemblePrograms extends Model
{
    private array $programs =[];

    public function __construct(private readonly int $libItemId)
    {
        $this->init();
    }

    public function getPrograms(): array
    {
        return $this->programs;
    }

    private function init(): void
    {
        //a:3:{i:0;O:8:"stdClass":2:{s:9:"programId";i:26;s:10:"ensembleId";i:22;}i:1;O:8:"stdClass":2:{s:9:"programId";i:28;s:10:"ensembleId";i:22;}i:2;O:8:"stdClass":2:{s:9:"programId";i:41;s:10:"ensembleId";i:9;}}
        $programAndEnsembleIds = $this->getProgramAndEnsembleIds($this->libItemId);

        foreach($programAndEnsembleIds as $ids){
            $program = Program::find($ids->programId);
            $ensemble = Ensemble::find($ids->ensembleId);
            $this->programs[] = [
                'program' => $program,
                'ensemble' => $ensemble,
                'selections' => $program->getEnsembleSelectionsVO($ensemble->id)
            ];
        }
    }

    private function getProgramAndEnsembleIds(int $libItemId): array
    {
        return DB::table('program_selections')
            ->join('programs', 'programs.id', '=', 'program_selections.program_id')
            ->where('lib_item_id', $libItemId)
            ->select(
                'programs.id AS programId', 'program_selections.ensemble_id AS ensembleId'
            )
            ->orderByDesc('programs.school_year')
            ->get()
            ->toArray();
    }


}
