<?php

namespace App\Livewire\Forms;

use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
    public string $participationFeeOpen = '';
    public string $participationFeeClose = '';
    public string $postmarkDeadline = '';
    public string $rehearsalClose = '';
    public string $rehearsalOpen = '';
    public string $studentClose = '';
    public string $studentOpen = '';
    public string $sysId = 'new';
    public string $tabRoomClose = '';
    public string $tabRoomOpen = '';

    private Version $mostRecentVersion;

    public function rules(): array
    {
        return [
            'adjudicationOpen' => 'required|date',//|before:adjudicationClose',
            'adjudicationClose' => 'required|date|after:adjudicationOpen',
            'adminOpen' => 'required|date',//|before:adminClose',
            'adminClose' => 'required|date|after:adminOpen',
            'finalTeacherChanges' => 'required|date',
            'membershipOpen' => 'required|date',//|before:membershipClose',
            'membershipClose' => 'required|date|after:membershipOpen',
            'participationFeeOpen' => 'required|date',//|before:participationFeeClose',
            'participationFeeClose' => 'required|date|after:membershipOpen',
            'postmarkDeadline' => 'required|date',
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
            'participation_fee_close', 'participation_fee_open',
            'postmark_deadline',
            'rehearsal_open', 'rehearsal_close',
            'student_close', 'student_open',
            'tab_room_close', 'tab_room_open',
        ];

        $vcds = collect();

        //get any existing date rows
        if (VersionConfigDate::where('version_id', $versionId)->exists()) {
            $vcds = VersionConfigDate::where('version_id', $versionId)->get();
        }

        //loop through $dateTypes
        //if date_type is found in the current version_id, use that to populate the dateType variable
        //else clone that data_type from the most recent previous version
        foreach ($dateTypes as $dateType) { //ex. adjudication_close

            if (!$this->setDateTypeToDbValue($vcds, $dateType)) {

                $this->setDateTypeToClonedValue($versionId, $dateType);
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

    private function cloneOrCreate(string $var): string
    {
        $date_type = Str::snake($var); //ex: adjudication_close
        //Carbon object or null
        $dateToClone = VersionConfigDate::query()
            ->where('version_id', $this->mostRecentVersion->id)
            ->where('date_type', $date_type)
            ->first();

        if ($dateToClone) { //add a year to the current value

            return Carbon::parse($dateToClone->version_date)->addYear()->format('Y-m-d H:i:s');

        } else { //set value to 7am of the current date

            return Carbon::now()
                ->setTime(7, 0, 0)
                ->format('Y-m-d H:m:s');
        }
    }

    private function setDateTypeToClonedValue(int $versionId, string $date_type): void
    {
        //format $date_type to $dateType
        $dateType = Str::camel($date_type);

        //find the most recent previous version
        $this->setMostRecentVersion();

        //set the $default value to (previous version's value + one year) or the current date
        $clonedDate = $this->cloneOrCreate($date_type);

        //create row
        VersionConfigDate::create([
            'version_id' => $versionId,
            'date_type' => $date_type,
            'version_date' => $clonedDate,
        ]);

        //set $this->dateType to the found value
        $this->$dateType = $clonedDate;
    }

    private function setDateTypeToDbValue(Collection $vcds, string $date_type): bool
    {
        //transform $dateType to $date_type
        $dateType = Str::camel($date_type); //ex adjudication_close === adjudicationClose

        //determine if $date_type exists for the current version_id
        $versionDate = $vcds->where('date_type', $date_type)->first();

        //if yes, use that $date_type to populate $this->dateType
        if ($versionDate) {

            $this->$dateType = $versionDate->version_date;//'Y-m-d H:i:s';

            return true;
        }

        return false;
    }

    private function setMostRecentVersion(): void
    {
        $currentVersion = Version::find($this->sysId);
        $event = Event::find($currentVersion->event_id);

        $this->mostRecentVersion = $event->versions()
            ->whereNot('id', $this->sysId)
            ->first() ?: $event->versions()->first();
    }

}
