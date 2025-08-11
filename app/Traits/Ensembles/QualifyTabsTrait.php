<?php

namespace App\Traits\Ensembles;

use App\Models\Libraries\Items\Components\LibItemLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait QualifyTabsTrait
{
    /**
     * Temporarily restrict access to assets and inventory to the Founder
     * @return string[]
     */
    protected function qualifyTabs(array $tabs): array
    {
        //from public const ENSEMBLETABS = ['ensembles', 'members', 'assets', 'inventory', 'library'];
        if (auth()->user()->isFounder()) {
            return $tabs;
        }

        return ['ensembles', 'members', 'library'];
    }

}
