<?php

namespace App\Jobs;

use App\Mail\TeacherCreateEmailMail;
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
        $user = User::find(368);

        Mail::to($email)->send(new TeacherCreateEmailMail($user, $this->teacher, storage_path("app/$filePath")));
    }
}
