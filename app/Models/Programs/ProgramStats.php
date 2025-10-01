<?php

namespace App\Models\Programs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgramStats extends Model
{
    public function __construct(
        private readonly int $schoolYear=0,
        private readonly string $voicing='all',
        private readonly array $voicingIds=[],
        private readonly bool $acappella=false,
        private readonly bool $jazz=false,
    ){}

    public function getStats(): array
    {
        return [
            'uniqueSchools' => $this->getUniqueSchools(),
            'uniqueProgramsWithLibraryItems' => $this->getUniqueProgramsWithLibraryItems(),
            'uniqueSongs' => $this->getUniqueLibraryItems(),
            'songsSungInMultipleSchools' => $this->getSongsSungInMultipleSchools(),
        ];
    }

    private function addWhenAcappella($query)
    {
        return ($this->accappella)
            ? $query->when($this->acapella, function($query) {
                return $query->where('ensembles.acappella', 1);
            })
            : $query;
    }

    private function addWhenJazz($query)
    {
        return ($this->jazz)
            ? $query->when($this->jazz, function($query) {
                return $query->where('ensembles.jazz', 1);
            })
            : $query;
    }

    private function addWhenSchoolYear($query)
    {
        return ($this->schoolYear)
            ? $query->when($this->schoolYear, function($query) {
                    return $query->where('programs.school_year', $this->schoolYear);
                })
            : $query;
    }

    private function addWhenVoicing($query)
    {
        return ($this->voicing)
            ? $query->when($this->voicing !== 'all', function($query) {
                 return $query->whereIn('lib_items.voicing_id', $this->voicingIds);
                })
            : $query;
    }

    private function baseQuery(string $select)
    {
        $query = DB::table('program_selections')
            ->join('programs', 'program_selections.program_id', '=', 'programs.id')
            ->join('lib_items', 'lib_items.id', '=', 'program_selections.lib_item_id')
            ->join('ensembles', 'ensembles.id', '=', 'program_selections.ensemble_id');

        $query = $this->addWhenSchoolYear($query);
        $query = $this->addWhenVoicing($query);
        $query = $this->addWhenAcappella($query);
        $query = $this->addWhenJazz($query);

        return $query->select($select);
    }

    private function getSongsSungInMultipleSchools(): array
    {
        $query = $this->baseQuery('program_selections.lib_item_id');

        $count =  $query
            ->groupBy('program_selections.lib_item_id')
            ->havingRaw('COUNT(DISTINCT programs.school_id) > 1')
            ->count();

        return [
            'label' => 'Songs sung in multiple schools',
            'count' =>  $count,
        ];

    }

    private function getUniqueLibraryItems(): array
    {
        $query = $this->baseQuery('program_selections.lib_item_id');

        $count = $query
            ->distinct()
            ->get()
            ->count();

        return [
            'label' => 'Unique songs',
            'count' =>  $count,
        ];
    }

    private function getUniqueProgramsWithLibraryItems(): array
    {
        $query = $this->baseQuery('program_selections.program_id');

        $count = $query
            ->distinct()
            ->get()
            ->count();

        return [
            'label' => 'Unique programs with songs',
            'count' =>  $count,
        ];
    }

    private function getUniqueSchools(): array
    {
        $query = $this->baseQuery('programs.school_id');

        $count = $query
            ->distinct()
            ->get()
            ->count();

        return [
            'label' => 'Unique Schools contributing programs',
            'count' =>  $count,
        ];
    }
}
