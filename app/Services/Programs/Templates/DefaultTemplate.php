<?php

namespace App\Services\Programs\Templates;

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

//        $str .= '<thead>';
//
//        $str .= '<tr>';
//
//        $str .= '<th>#</th>';
//
//        $str .=  '</tr>';
//
//        $str .= '</thead>';
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
        //early exit
        if ($selection->ensemble_id == $this->ensembleId) {
            return '';
        } else { //reset the target var
            $this->ensembleId = $selection->ensemble_id;
        }

        //create the header row
        $str = '<tr>';

        $str .= '<th colspan="2" class=" w-full text-left">'.$selection->ensembleName.'</th>';

        $str .= '</tr>';

        return $str;
    }

    private function setSelectionRow($selection): string
    {
        $str = '<tr class="">';

        $str .= '<td class="w-1/2 text-sm text-left pl-1">'
            ."<button wire:click='clickSelection($selection->id)' class='text-blue-500'>"
            .$selection->title
            .'</button>'
            .'</td>';
        $str .= '<td class="w-1/2 text-sm text-right">'.$selection->artistBlock.'</td>';
        $str .= '</tr>';
        return $str;
    }

    public function getTable(): string
    {
        return $this->table;
    }
}
