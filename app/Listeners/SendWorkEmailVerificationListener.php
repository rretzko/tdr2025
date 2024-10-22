<?php

namespace App\Listeners;

use App\Events\WorkEmailChangedEvent;
use App\Mail\WorkEmailVerificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $to = $event->schoolTeacher->email;
        Mail::to($to)->send(new WorkEmailVerificationMail($event));

        //Log::info($event->workEmail);
        //Log::info($event->schoolTeacher->teacher_id);
    }
}
