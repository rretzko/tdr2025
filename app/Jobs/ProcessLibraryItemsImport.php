<?php

namespace App\Jobs;

use App\Imports\LibraryItemsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessLibraryItemsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $libraryId;
    protected string $filePath;
    protected int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $libraryId, string $filePath, $userId)
    {
//        Log::info(__CLASS__ . ': ' . __METHOD__ . ': ' . __LINE__);
        $this->libraryId = $libraryId;
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info(__METHOD__.' started');
        try {
            Excel::import(
                new LibraryItemsImport($this->libraryId, $this->userId),
                $this->filePath,
                's3',
                \Maatwebsite\Excel\Excel::CSV
            );
            Log::info('Excel import completed successfully');
        } catch (\Exception $e) {
            Log::error('Excel import failed: '.$e->getMessage());
            // Optionally, you can add retry or failure handling here
        }
        Log::info(__METHOD__.' finished');
    }
}
