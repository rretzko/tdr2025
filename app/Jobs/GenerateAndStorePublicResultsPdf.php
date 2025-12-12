<?php

namespace App\Jobs;

use App\Models\Students\VoicePart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Data\Pdfs\PdfScoringRosterDataFactory;
class GenerateAndStorePublicResultsPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $versionId;
    public $timeout = 600; // 10 minutes
    public $tries = 1; // Only try once - no point retrying if it times out
    public $maxExceptions = 1; // Stop after first exception

    /**
     * Create a new job instance.
     */
    public function __construct(int $versionId)
    {
        $this->versionId = $versionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            // Increase PHP execution time for this job
            set_time_limit(600);
            ini_set('memory_limit', '512M'); // Increase if needed

            $version = \App\Models\Events\Versions\Version::findOrFail($this->versionId);

            // Generate your PDF content
            $data = $this->prepareData($version);
            $dto = $data['dto'];
            $rows = $data['rows'];
            $rowsScores = $data['rowsScores'];

            $pdf = Pdf::loadView('pdfs.tabrooms.scoringRoster', compact('dto', 'rows', 'rowsScores'))
                ->setPaper('letter', 'portrait')
                ->setOption('enable_php', false) // Disable PHP execution in HTML
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', false); // Disable remote resources

            $filename = "combinedConfidentialPdfs/combinedConfidential_{$this->versionId}.pdf";

            // This will overwrite if exists
            Storage::disk('s3')->put(
                $filename,
                $pdf->output(),
                'public'
            );

        } catch (\Exception $e) {
            \Log::error('PDF generation failed', [
            'version_id' => $this->versionId,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
            ]);

            throw $e; // Re-throw so job is marked as failed
        }

    }

    private function prepareData($version)
    {
        // Gather all data needed for the PDF
        $data = new PdfScoringRosterDataFactory(
            $this->versionId,
            null, //all voice parts
            0,
            false
        );

        $dto = $data->getDto();
        $rows = $dto['rows'];
        $rowsScores = $dto['rowsScores'];
        return [
            'version' => $version,
            'dto' => $dto,
            'rows' => $rows,
            'rowsScores' => $rowsScores
        ];
    }

    public function failed(\Throwable $exception)
    {
        // This method is called when the job finally fails after all retries
        \Log::error('PDF generation job failed permanently', [
            'version_id' => $this->versionId,
            'error' => $exception->getMessage()
        ]);
    }
}
