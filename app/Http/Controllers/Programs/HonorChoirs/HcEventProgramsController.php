<?php

namespace App\Http\Controllers\Programs\HonorChoirs;

use App\Http\Controllers\Controller;
use App\Models\Programs\HonorChoirs\HcEvent;
use Illuminate\Http\Request;

class HcEventProgramsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, HcEvent $hcEvent)
    {
        $prevEventId = $hcEvent->previousEventId();
        $prevYearOf = HcEvent::find($prevEventId)?->year_of ?? 0;
        $nextEventId = $hcEvent->nextEventId();
        $nextYearOf = HcEvent::find($nextEventId)?->year_of ?? 0;
        return view('livewire.programs.honor-choir-program-view',
            compact(
                'hcEvent',
                'prevEventId',
                'prevYearOf',
                'nextEventId',
                'nextYearOf'));
    }
}
