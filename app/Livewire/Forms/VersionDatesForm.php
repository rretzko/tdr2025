<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\VersionConfigDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionDatesForm extends Form
{
    public string $adjudicationClose = '';
    public string $adjudicationOpen = '';
    public string $adminClose = '';
    public string $adminOpen = '';
    public string $finalTeacherChanges = '';
    public string $membershipClose = '';
    public string $membershipOpen = '';
    public string $studentClose = '';
    public string $studentOpen = '';
    public string $sysId = 'new';
    public string $tabRoomClose = '';
    public string $tabRoomOpen = '';


    public function setDates(int $versionId): void
    {
        //sysId here is a proxy for $versionId
        $this->sysId = $versionId;

        $dateTypes = [
            'adjudication_close', 'adjudication_open',
            'admin_close', 'admin_open',
            'final_teacher_changes',
            'membership_close', 'membership_open',
            'student_close', 'student_open',
            'tab_room_close', 'tab_room_open',
        ];

        $vcds = collect();

        //get any existing date rows
        if (VersionConfigDate::where('version_id', $versionId)->exists()) {
            $vcds = VersionConfigDate::where('version_id', $versionId)->get();
        }

        foreach ($dateTypes as $dateType) {

            $var = Str::camel($dateType); //ex admin_close === adminClose

            $versionDate = $vcds->where('date_type', $dateType)->first();

            if ($versionDate) {

                $this->$var = Carbon::parse($versionDate->version_date)->format('Y-m-d');

            } else {

                //create row
                VersionConfigDate::create([
                    'version_id' => $versionId,
                    'date_type' => $dateType,
                    'version_date' => Carbon::now()->format('Y-m-d')
                ]);

                $this->$var = VersionConfigDate::where('version_id', $versionId)
                    ->where('date_type', $dateType)
                    ->first()->version_date;
            }
        }
    }

    public function updateDate(string $date_type, string $value): int
    {
        return VersionConfigDate::query()
            ->where('version_id', $this->sysId)
            ->where('date_type', $date_type)
            ->update(['version_date' => Carbon::parse($value)->format('Y-m-d')]);

    }

}
