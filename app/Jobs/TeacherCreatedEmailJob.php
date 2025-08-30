<?php

namespace App\Jobs;

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
    public function __construct($model)
    {
        Log::info('*** modelId: ' . $model->id . ' ***');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find(368);
        $email = 'rick@mfrholdings.com';
        Log::info('*** email: ' . $email . ' ***');
        //Mail::to($email)->send(new SendScoringRosterMail($user, storage_path("app/$filePath")));
    }
}
