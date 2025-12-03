<?php

namespace App\Exports;

use App\Models\EmergencyContact;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventEnsembleParticipantsExport implements FromArray, WithHeadings
{
    public function __construct(private readonly array $participants)
    {
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $updatedParticipants = [];
        foreach ($this->participants as $key => $participant) {
            $updatedParticipants[] = $this->addEmergencyContacts($key, $participant);
        }

        return $updatedParticipants;
    }

    public function headings(): array
    {
        return [
            'name',
            'lastName',
            'school',
            'teacher',
            'vp',
            'vp-sort',
            'score',
            'student email',
            'student cell',
            'student home',
            'teacher email',
            'teacher cell',
            'teacher work',
            'emergency contact',
            'ec email1',
            'ec cell1',
            'ec home1',
            'ec work1',
            'ec name2',
            'ec email2',
            'ec cell2',
            'ec home2',
            'ec work2',
            'ec name3',
            'ec email3',
            'ec cell3',
            'ec home3',
            'ec work3',
        ];
    }

    private function addEmergencyContacts(int $key, \stdClass $participant): \stdClass
    {
        $studentId = $participant->studentId;
        $ecs = EmergencyContact::where('student_id', $studentId)->get();
        $updatedParticipant = null;

        //early exit
        if($ecs->count() < 2) {
            unset($participant->studentId);
            return $participant;
        }

        foreach($ecs AS $cntr => $ec){
            $ecNdx = $cntr + 1;
            if($ec->phone_mobile != $participant->phoneMobileEC){

                $labelName = 'Ec'. $ecNdx . 'Name';
                $participant->{$labelName} = $ec->name;
                $labelEmail = 'Ec'. $ecNdx . 'Email';
                $participant->{$labelEmail} = $ec->email;
                $labelMobile = 'phoneMobileED'. $ecNdx;
                $participant->{$labelMobile} = $ec->phone_mobile;
                $labelHome = 'phoneHomeED'. $ecNdx;
                $participant->{$labelHome} = $ec->phone_home;
                $labelWork = 'phoneWorkED'. $ecNdx;
                $participant->{$labelWork} = $ec->phone_work;
            }
        }

        unset($participant->studentId);

        return $participant;


    }
}
