<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\VersionConfigDate;
use Carbon\Carbon;
use Illuminate\Support\Str;
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

    public function rules(): array
    {
        return [
            'adjudicationOpen' => 'required|date',//|before:adjudicationClose',
            'adjudicationClose' => 'required|date|after:adjudicationOpen',
            'adminOpen' => 'required|date',//|before:adminClose',
            'adminClose' => 'required|date|after:adminOpen',
            'finalTeacherChanges' => 'required' | 'date',
            'membershipOpen' => 'required|date',//|before:membershipClose',
            'membershipClose' => 'required|date|after:membershipOpen',
            'studentOpen' => 'required|date',//|before:studentClose',
            'studentClose' => 'required|date|after:studentOpen',
            'tabRoomOpen' => 'required|date',//|before:tabRoomClose',
            'tabRoomClose' => 'required|date|after:tabRoomOpen',
        ];
    }

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

        foreach ($dateTypes as $dateType) { //ex. adjudication_close

            $var = Str::camel($dateType); //ex adjudication_close === adjudicationClose

            $versionDate = $vcds->where('date_type', $dateType)->first();

            if ($versionDate) {

                $this->$var = $versionDate->version_date;//->format('Y-m-d h:i a');

            } else {

                //defaults
                $defaults = [
                    'adjudicationOpen' => Carbon::now()->setTime(7, 0, 0),
                    'adjudicationClose' => Carbon::now()->setTime(15, 30, 0),
                    'adminOpen' => Carbon::now()->setTime(0, 0, 1),
                    'adminClose' => Carbon::now()->setTime(23, 59, 59),
                    'finalTeacherChanges' => Carbon::now()->setTime(0, 30, 0),
                    'membershipOpen' => Carbon::now()->setTime(0, 0, 1),
                    'membershipClose' => Carbon::now()->setTime(15, 30, 0),
                    'studentOpen' => Carbon::now()->setTime(0, 0, 1),
                    'studentClose' => Carbon::now()->setTime(15, 30, 0),
                    'tabRoomOpen' => Carbon::now()->setTime(0, 0, 1),
                    'tabRoomClose' => Carbon::now()->setTime(23, 59, 59),
                ];

                //create row
                VersionConfigDate::create([
                    'version_id' => $versionId,
                    'date_type' => $dateType,
                    'version_date' => $defaults[Str::camel($dateType)],
                ]);

                $this->$var = VersionConfigDate::where('version_id', $versionId)
                    ->where('date_type', $dateType)
                    ->first()->version_date;
            }
        }

    }

    public function updateDate(string $date_type, string $value): int
    {
        $this->validateOnly(Str::camel($date_type));

        if (Str::contains($value, 'T')) {//has time element

            $versionDate = Carbon::parse($value)->format('Y-m-d H:i:s');

        } else { //add a time element defaulting to 11:59:59 pm of $value

            $versionDate = Carbon::createFromFormat('Y-m-d', $value);
            (Str::contains($date_type, 'open'))
                ? $versionDate->setTime(0, 0, 1)
                : $versionDate->setTime(23, 59, 59);
        }

        return VersionConfigDate::query()
            ->where('version_id', $this->sysId)
            ->where('date_type', $date_type)
            ->update(['version_date' => $versionDate]);

    }

}
