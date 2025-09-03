<?php

namespace App\Jobs;

use App\Mail\LibStackCreatedEmailMail;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class LibStackCreatedEmailJob implements ShouldQueue
{
    use Queueable;

    public LibItem $libItem;
    public Library $library;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly LibStack $libStack)
    {
        $this->libItem = LibItem::find($libStack->lib_item_id);
        $this->library = Library::find($libStack->library_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $founder = User::find(config('app.founderId'));

        Mail::to($founder->email)->send(new LibStackCreatedEmailMail($founder, $this->library, $this->libItem));
    }
}
