<?php

namespace App\Jobs;

use App\Mail\TeacherCreateEmailMail;
use App\Models\Schools\Teacher;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TeacherCreatedEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Teacher $teacher)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $founder = User::find(config('app.founderId'));

        Mail::to($founder->email)->send(new TeacherCreateEmailMail($founder, $this->teacher));
    }
}
