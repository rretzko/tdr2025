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

    public function findCandidateScorePath(Candidate $candidate): string
    {
        $versionId = $candidate->version_id;
        $header = "../resources/views/";
        $path = "pdfs/candidateScores/versions/$versionId}/pdf.blade.php";
        $file = $header.$path;
        $view = "pdfs.candidateScores.versions.$versionId.pdf";

        if (file_exists($file)) {
            return $view;
        }

        $version = Version::find($versionId);
        if ($version) {
            $eventId = $version->event_id;
            return "pdfs.candidateScores.events.{$eventId}.pdf";
        }

        // Optionally handle the case where the version is not found
        throw new \Exception("Version with ID {$versionId} not found.");
    }

    public function findContractPath(Candidate $candidate): string
    {
        $versionId = $candidate->version_id;
        $header = "../resources/views/";
        $path = "pdfs/contracts/versions/{$versionId}/pdf.blade.php";
        $file = $header.$path;
        $view = "pdfs.contacts.versions.{$versionId}.pdf";

        if (file_exists($file)) {
            return $view;
        }

        $version = Version::find($versionId);
        if ($version) {
            $eventId = $version->event_id;
            return "pdfs.contracts.events.{$eventId}.pdf";
        }

        // Optionally handle the case where the version is not found
        throw new \Exception("Version with ID {$versionId} not found.");
    }

    public function findEstimatePath(Version $version): string
    {
        $versionId = $version->id;
        $header = "../resources/views/";
        $path = "pdfs/estimates/versions/{$versionId}/pdf.blade.php";
        $file = $header.$path;
        $view = "pdfs.estimates.versions.{$versionId}.pdf";

        if (file_exists($file)) {
            return $view;
        }

        $version = Version::find($versionId);
        if ($version) {
            $eventId = $version->event_id;
            return "pdfs.estimates.events.{$eventId}.pdf";
        }

        // Optionally handle the case where the version is not found
        throw new \Exception("Version with ID {$versionId} not found.");
    }
}
