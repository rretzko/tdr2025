<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;

class FindPdfPathService
{
    public function findApplicationPath(Candidate $candidate): string
    {
        $versionId = $candidate->version_id;
        $header = "../resources/views/";
        $path = "pdfs/applications/versions/{$versionId}/pdf.blade.php";
        $file = $header.$path;
        $view = "pdfs.applications.versions.{$versionId}.pdf";

        if (file_exists($file)) {
            return $view;
        }

        $version = Version::find($versionId);
        if ($version) {
            $eventId = $version->event_id;
            return "pdfs.applications.events.{$eventId}.pdf";
        }

        // Optionally handle the case where the version is not found
        throw new \Exception("Version with ID {$versionId} not found.");
    }
}
