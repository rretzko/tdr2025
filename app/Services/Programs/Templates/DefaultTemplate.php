<?php

namespace App\Services\Programs\Templates;

use App\Models\Programs\ProgramAddendum;
use App\Models\Programs\ProgramSelection;
use Illuminate\Support\Collection;

class DefaultTemplate
{
    private int $ensembleId = 0;
    private ProgramSelection $programSelection;
    private Collection $selections;
    private string $table = '';

    public function __construct(private readonly int $programId)
    {
        $this->selections = $this->getSelections();
        $this->table = $this->makeTable();
    }

    public function getTable(): string
    {
        return $this->table;
    }

    private function addendums(int $programSelectionId): string
    {
        $addendums = ProgramAddendum::where('program_selection_id', $programSelectionId)->get();

        //early exit
        if (is_null($addendums)) {
            return '';
        }

        $str = '<tr>';
        $str .= '<td colspan="2" class="text-center text-xs italic">';
        $str .= '<div class="flex flex-col">';
        foreach ($addendums as $addendum) {
            $str .= '<div>'.$addendum->addendum.'</div>';

        }
        $str .= '</div>';
        $str .= '</td>';
        $str .= '</tr>';

        return $str;
    }

    private function getSelections(): Collection
    {
        return ProgramSelection::query()
            ->where('program_id', $this->programId)
            ->orderBy('order_by')
            ->get();
    }

    private function makeTable(): string
    {
        $str = '<table class="w-full">';

        $str .= '<tbody>';
        foreach ($this->selections as $selection) {

            $str .= $this->setEnsembleHeader($selection);

            $str .= $this->setSelectionRow($selection);
        }

        $str .= '</tbody>';
        $str .= '</table>';

        return $str;
    }

    private function setEnsembleHeader($selection): string
    {
        //switch
        static $firstHeader = true;

        //early exit
        if ($selection->ensemble_id == $this->ensembleId) {
            return '';
        } else { //reset the target var
            $this->ensembleId = $selection->ensemble_id;
        }

        //create the header row
        $str = '<tr>';

        $str .= '<td colspan="2" class=" w-full text-left font-semibold">'
            .'<button wire:click="setDisplayEnsembleStudentRoster('.$this->ensembleId.')">'
            .$selection->ensembleName
            .'</button>';

        if ($firstHeader) {
            $str .= '<hint class="ml-2 text-xs italic">(Click Ensemble name to display student members.)</hint>';
            $firstHeader = false;
        }

        $str .= '</td>';

        $str .= '</tr>';

        return $str;
    }

    private function setSelectionRow($selection): string
    {
        $str = '<tr class="">';

        $str .= '<td class="w-1/2 text-sm text-left pl-1 align-top">'
            ."<button wire:click='clickSelection($selection->id)' class='text-blue-500'>"
            .$selection->title
            .'</button>'
            .'</td>';
        $str .= '<td class="w-1/2 text-sm text-right">'.$selection->artistBlock.'</td>';
        $str .= '</tr>';
        $str .= $this->addendums($selection->id);
        return $str;
    }


}
