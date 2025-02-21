<?php

namespace App\Jobs;

use App\Mail\SendScoringRosterMail;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LargePdfProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array  $rows,
        private readonly array  $rowsScores,
        private readonly string $fileName,
        private readonly array  $dto,
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Generate PDF
        $pdf = app('dompdf.wrapper')->loadView('pdfs.tabrooms.scoringRoster', [
            'rows' => $this->rows,
            'rowsScores' => $this->rowsScores,
            'dto' => $this->dto,
        ])->setPaper('letter', 'landscape');

        // Save PDF to storage
        $filePath = 'scoring_rosters/' . $this->fileName;
        Storage::disk('local')->put($filePath, $pdf->output());

        // Send email with the PDF attachment
        dd(auth()->user());
        $emailAddress = auth()->user()->email;
        $user = User::find(368);
        $email = 'rick@mfrholdings.com';
        Mail::to($email)->send(new SendScoringRosterMail($user, storage_path("app/$filePath")));
    }
}
