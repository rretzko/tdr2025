<?php

namespace App\Imports;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use App\Services\Ensembles\AddNewEnsembleMemberService;
use App\Services\Programs\EnsembleMemberRosterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Traits\MakeUniqueEmailTrait;

class EnsembleMembersImport implements ToModel, WithHeadings
{
    use MakeUniqueEmailTrait;

    /**
     * @param  array  $row
     * array:8 [▼
     * 0 => "school year"
     * 1 => "ensemble"
     * 2 => "first name"
     * 3 => "middle name"
     * 4 => "last name"
     * 5 => "email"
     * 6 => "grade/classOf"
     * 7 => "voice part"
     * ]
     */
    public function model(array $row)
    {
        static $counter = 0;
        if ($row[0] !== 'school year') { //skip header row

            static $schoolId = 0;
            static $ensembleId = 0;
            $voicePartId = 0;
            $email = '';

            if (!$schoolId) {
                $schoolId = UserConfig::getValue('schoolId');
            }

            if ($schoolId && !$ensembleId) {
                $ensembleId = $this->getEnsembleId($row[1], $schoolId);
            }

            if ($ensembleId) {
                $voicePartId = $this->getVoicePartId($row[7]);
            } else {
                Log::error(__CLASS__.': ensembleId for "'.$row[1].'" not found for schoolId: '.$schoolId);
            }

            if ($voicePartId) {
                $email = $this->getEmail($row[5], $row[2], $row[4]);
            } else {
                Log::info(__CLASS__.': voicePartId is missing for '.$row[7]).'.';
            }

            if ($email) {

                $service = new AddNewEnsembleMemberService(
                    $schoolId,
                    $ensembleId,
                    $row[0],    //school year
                    $row[2],    //first name
                    $row[3],    //middle name
                    $row[4],    //last name
                    $email,    //email
                    $row[6],    //grade/classOf
                    $voicePartId,    //voice part
                );
            } else {
                Log::info(__CLASS__.': email is missing for '.$row[5]).'.';
            }
        }
    }

    private function getEnsembleId(string $ensembleName, int $schoolId)
    {
        return Ensemble::where('name', $ensembleName)
            ->where('school_id', $schoolId)
            ->first()?->id ?? 0;
    }

    private function getVoicePartId(string|null $voicePart): int
    {
        //early exit
        if (empty($voicePart)) {
            return 63; //soprano I default
        }

        return (strlen($voicePart) < 4)
            ? VoicePart::where('abbr', $voicePart)->first()?->id ?? 0
            : VoicePart::where('descr', $voicePart)->first()?->id ?? 0;
    }

    private function getEmail(string|null $email, string $firstName, string $lastName): string
    {
        //early exit
        if (strlen($email)) {
            return $email;
        }

        return $this->makeUniqueEmail($firstName, $lastName);
    }

    public function headings(): array
    {
        return [
            'school_year',
            'ensemble',
            'first_name',
            'middle_name',
            'last_name',
            'grade/classOf',
        ];
    }
}
