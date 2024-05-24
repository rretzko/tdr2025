<?php

namespace App\Listeners;

use App\Events\WorkEmailChangedEvent;
use Illuminate\Support\Facades\Log;

class SendWorkEmailVerificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WorkEmailChangedEvent $event): void
    {
        Log::info($event->workEmail);
        Log::info($event->schoolTeacher->teacher_id);
    }
}
