<?php

namespace App\Livewire\Programs;

use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Programs\AnonymizedEnsemblePrograms;
use App\Models\Programs\ProgramStats;
use DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ChoirTrendsSchoolYearWidget extends Component
{
    public bool $acappella=false;
    public array $data = [];
    public bool $jazz=false;
    public int $programSearchLibItemId = 0;
    public int $schoolYear=0;
    public string $tableFor = '';
    public string $voicing='all';

    public function mount(): void
    {
        $this->data = $this->makeSchoolYearData();
        $this->resetTableFor();
    }
    public function render()
    {
        return view('livewire..programs.choir-trends-school-year-widget',
        [
            'topTen' => $this->getTopTen(),
            'programs' => $this->getPrograms(),
        ]);
    }

    public function clickViewButton(int $libItemId): void
    {
        $this->programSearchLibItemId = $libItemId;
    }

    public function updatedAcappella(): void
    {
        $this->resetTableFor();

        $this->data = $this->makeSchoolYearData();
    }

    public function updatedJazz(): void
    {
        $this->resetTableFor();

        $this->data = $this->makeSchoolYearData();
    }

    public function updatedSchoolYear(): void
    {
        $this->resetTableFor();

        $this->data = $this->makeSchoolYearData();
    }

    public function updatedVoicing(): void
    {
        $this->resetTableFor();

        $this->data = $this->makeSchoolYearData();
    }

    private function getPrograms(): array
    {
        //early exit
        if(!$this->programSearchLibItemId){
            return [];
        }

        $programs = new AnonymizedEnsemblePrograms($this->programSearchLibItemId);

        return $programs->getPrograms();
    }

    private function getTopTen(): array
    {
        $query = DB::table('program_selections')
            ->select( 'lib_titles.title', DB::raw('COUNT(program_selections.lib_item_id) AS total'),
                'composers.artist_name AS composer',
                'arrangers.artist_name AS arranger',
                'voicings.descr AS voicing',
                'program_selections.lib_item_id'
            )
            ->join('programs', 'program_selections.program_id', '=', 'programs.id')
            ->join('lib_items', 'program_selections.lib_item_id', '=', 'lib_items.id')
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->join('voicings', 'lib_items.voicing_id', '=', 'voicings.id')
            ->join('ensembles', 'program_selections.ensemble_id', '=', 'ensembles.id')
            ->leftJoin('artists AS composers', 'lib_items.composer_id', '=', 'composers.id')
            ->leftJoin('artists AS arrangers', 'lib_items.arranger_id', '=', 'arrangers.id')
            ->groupBy('lib_titles.title', 'composers.artist_name', 'arrangers.artist_name','voicings.descr','program_selections.lib_item_id')
            ->when(
                $this->schoolYear > 0, function ($q) {
                    return $q->where('programs.school_year', $this->schoolYear);
                }
            )
            ->when(
                $this->voicing !== 'all', function ($q) {
                    $voicingIds = $this->getVoicingIds();
                    return $q->whereIn('lib_items.voicing_id', $voicingIds);
                }
            )
            ->when($this->acappella, function ($q) {
                    return $q->where('ensembles.acappella', '=', 1);
                }
            )
            ->when($this->jazz, function ($q) {
                    return $q->where('ensembles.jazz', '=', 1);
                }
            )
            ->orderBy('total', 'desc')
            ->orderBy('lib_titles.title', 'asc');


        $query = $query
            ->limit(10)
            ->get()
            ->toArray();

        return $query;
    }

    private function getVoicingIds(): array
    {
        $mixed = Voicing::where('descr', 'LIKE', '%satb%')->pluck('id')->toArray();
        $treble = Voicing::where('descr', 'LIKE', '%ss%')->pluck('id')->toArray();
        $ttbb = Voicing::where('descr', 'LIKE', '%tt%')->pluck('id')->toArray();
        $voicingIds = [
            'all' => [],
            'mixed' => $mixed,
            'treble' => $treble,
            'ttbb' => $ttbb,
        ];

        return $voicingIds[$this->voicing];
    }

    private function makeSchoolYearData(): array
    {
        $programStats = new ProgramStats(
            $this->schoolYear,
            $this->voicing,
            $this->getVoicingIds(),
            $this->acappella,
            $this->jazz
        );

        return $programStats->getStats();
    }

    private function resetTableFor(): void
    {
        //initialize
        $strs = [];
        $this->tableFor = '';

        //full reset
        if(($this->schoolYear == 0) && ($this->voicing == 'all') && (!$this->acappella) && (!$this->jazz)){

            $strs[] = 'all school years & all voicings';

        }else{ //customize

            if($this->schoolYear){
                $strs[] = 'school year ' . $this->schoolYear;
            }

            if($this->voicing !== 'all'){
                $strs[] = $this->voicing . ' voicing';
            }

            if($this->acappella){
                $strs[] = ' a cappella';
            }

            if($this->jazz){
                $strs[] = ' jazz';
            }
        }

        if(count($strs) === 1){
            $this->tableFor = ' ' . $strs[0];
        }else{
            $this->tableFor = implode('/', $strs);
        }
    }
}
