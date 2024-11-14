<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\UserConfig;
use Illuminate\Support\Collection;

class FindPdfPathService
{
    public function findAdjudicationBackupPaperPath(int $roomId): string
    {
        $versionId = UserConfig::getValue('versionId');
        $version = Version::find($versionId);

        //early exit
        if (!$version) {
            throw new \Exception("Version with ID {$versionId} not found.");
        }

        //version
        $header = "../resources/views/";
        $versionPath = "pdfs/adjudications/backupPapers/versions/{$versionId}/pdf.blade.php";
        $versionFile = $header.$versionPath;
        $view = "pdfs.adjudications.backupPapers.versions.{$versionId}.pdf";

        if (file_exists($versionFile)) {
            return $view;
        }

        //event
        $eventPath = "pdfs/adjudications/backupPapers/events.{$version->event_id}/pdf.blade.php";
        $eventFile = $header.$eventPath;
        $view = "pdfs.adjudications.backupPapers.events.{$version->event_id}.pdf";
        if (file_exists($eventFile)) {
            return $view;
        }

        //generic
        return "pdfs.adjudications.backupPapers.pdf";
    }

    public function findAdjudicationMonitorChecklistPath(int $roomId): string
    {
        $versionId = UserConfig::getValue('versionId');
        $version = Version::find($versionId);

        //early exit
        if (!$version) {
            throw new \Exception("Version with ID {$versionId} not found.");
        }

        //version
        $header = "../resources/views/";
        $versionPath = "pdfs/adjudications/monitorChecklists/versions/{$versionId}/pdf.blade.php";
        $versionFile = $header.$versionPath;
        $view = "pdfs.adjudications.monitorChecklists.versions.{$versionId}.pdf";

        if (file_exists($versionFile)) {
            return $view;
        }

        //event
        $eventPath = "pdfs/adjudications/monitorChecklists/events.{$version->event_id}/pdf.blade.php";
        $eventFile = $header.$eventPath;
        $view = "pdfs.adjudications.monitorChecklists.events.{$version->event_id}.pdf";
        if (file_exists($eventFile)) {
            return $view;
        }

        //generic
        return "pdfs.adjudications.monitorChecklists.pdf";
    }

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

    public function findCandidateScoresSchoolPath(Version $version): string
    {
        $versionId = $version->id;
        $header = "../resources/views/";
        $path = "pdfs/candidateScoresSchool/versions/$versionId}/pdf.blade.php";
        $file = $header.$path;
        $view = "pdfs.candidateScoresSchool.versions.$versionId.pdf";

        //if a versions/{$versionId}/pdf.blade.php file is found use that
        if (file_exists($file)) {
            return $view;
        }

        //otherwise, look for the file in the events directory
        $eventId = $version->event_id;
        $path = "pdfs/candidateScoresSchool/events/$eventId}/pdf.blade.php";
        $file = $header.$path;
        $view = "pdfs.candidateScoresSchool.events.$eventId.pdf";

        if (file_exists($file)) {
            return $view;
        }

        //lastly use the default pdf.blade.php file
        return "pdfs.candidateScoresSchool.pdf";
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

    public function findRegistrationCardPath(int $versionId): string
    {
        $versionId = ($versionId) ?: $this->getVersionId();

        //early exit
        if (!$versionId) {
            throw new \Exception("Version with ID {$versionId} not found.");
        }

        //version
        $versionFileTail = implode(DIRECTORY_SEPARATOR, ['pdfs', 'registrationCards', 'versions', $versionId]);

        $versionView = false; //$this->getVersionView($versionFileTail);

        //event
        $eventId = Version::find($versionId)->event->id;

        $eventFileTail = implode(DIRECTORY_SEPARATOR, ['pdfs', 'registrationCards', 'events', $eventId]);

        $eventView = false; //$this->getEventView($eventFileTail);
        /**
         * @todo this isn't working: results in default value when $versionView should be found
         */
        return match (true) {
            is_string($versionView) => $versionView,
            is_string($eventView) => $eventView,
            default => 'pdfs.registrationCards.pdf',
        };

    }

    private function getEventView(string $path, string $fileName = 'pdf'): string|bool
    {
        $viewFileName = $fileName.'.blade.php';

        $filePath = implode(DIRECTORY_SEPARATOR, [
            '..',
            'resources',
            'views',
            $path,
            $viewFileName,
        ]);

        $viewFilePath = str_replace(DIRECTORY_SEPARATOR, '.', $path)
            .'.'
            .$fileName;

        return (file_exists($filePath))
            ? $viewFilePath
            : false;
    }

    private function getVersionId(): int
    {
        return UserConfig::getValue('versionId') ?? 0;
    }

    private function getVersionView(string $path, string $fileName = 'pdf'): string|bool
    {
        $viewFileName = $fileName.'.blade.php';

        $filePath = implode(DIRECTORY_SEPARATOR, [
            '..',
            'resources',
            'views',
            $path,
            $viewFileName,
        ]);

        $viewFilePath = str_replace(DIRECTORY_SEPARATOR, '.', $path)
            .'.'
            .$fileName;

        return (file_exists($filePath))
            ? $viewFilePath
            : false;
    }
}
