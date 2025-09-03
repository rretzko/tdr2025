<?php

namespace App\Jobs;

use App\Mail\LibraryCreatedEmailMail;
use App\Models\Libraries\Library;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class LibraryCreatedEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Library $library)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $founder = User::find(config('app.founderId'));

        Mail::to($founder->email)->send(new LibraryCreatedEmailMail($founder, $this->library));
    }
}
