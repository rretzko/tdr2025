<?php

namespace App\Services;

use App\Models\Address;
use App\Models\EmergencyContact;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use Illuminate\Support\Carbon;

class CalcApplicationRequirements
{
    private array $missings = [];
    private Event $event;
    private Version $version;

    public function __construct(private readonly Candidate $candidate)
    {
        $this->version = Version::find($this->candidate->version_id);
        $this->event = $this->version->event;
        $this->init();
    }

    private function init(): void
    {
        $this->evaluateStatus();
        $this->evaluateVoicePartId();
        $this->evaluateEmergencyContact();
        $this->evaluateEmergencyContactPhoneMobile();
        $this->evaluateHomeAddress();
    }

    private function evaluateHomeAddress(): void
    {
        if ($this->version->student_home_address) {

            $address = Address::where('user_id', $this->candidate->student->user->id)->first();

            if (!$address) {
                $this->missings[] = "The candidate home address is missing.";

            } elseif (!
            (
                strlen($address->address1) &&
                strlen($address->city) &&
                strlen($address->geostate_id) &&
                strlen($address->postal_code)
            )
            ) {
                $this->missings[] = "The candidate home address is missing or only partially completed.";
            } else {

                //all good, do nothing
            }
        }
    }

    private function evaluateStatus(): void
    {
        $immutables = ['prohibited', 'removed', 'withdrew'];
        if (in_array($this->candidate->status, $immutables)) {

            $this->missings[] = "The candidate status is '{$this->candidate->status}' in this event version.";
        }
    }

    private function evaluateVoicePartId(): void
    {
        $service = new EventEnsemblesVoicePartsArrayService($this->event->eventEnsembles);
        $voiceParts = $service->getArray();
        $voicePartId = $this->candidate->voice_part_id;

        if (!$voicePartId || !array_key_exists($voicePartId, $voiceParts)) {

            $this->missings[] = 'Incorrect or missing voice part. ';//('.Carbon::now()->format('H:i:s').')';
        }
    }

    private function evaluateEmergencyContact(): void
    {
        if (!$this->candidate->student->emergencyContacts()->count()) {

            $this->missings[] = 'No emergency contact found. ';//('.Carbon::now()->format('H:i:s').')';
        }
    }

    /**
     * Add a row to $this->missings if at least one emergency contact is found
     * but without a phoneMobile value
     * @return void
     */
    private function evaluateEmergencyContactPhoneMobile(): void
    {
        //emergency contact identified
        if ($this->candidate->emergency_contact_id) {
            $ec = EmergencyContact::find($this->candidate->emergency_contact_id);
            if ((!$ec->phone_mobile) || (strlen($ec->phone_mobile) < 14)) {
                $this->missings[] = "No or invalid emergency contact cell phone found.";
            }
            return;
        }

        //emergency contact not identified
        if ($this->candidate->student->emergencyContacts()->count()) {

            if ($this->candidate->student->emergencyContacts()
                ->where('phone_mobile', "")
                ->count()
            ) {
                $this->missings[] = 'No emergency contact cell phone found. ';//('.Carbon::now()->format('H:i:s').')';
            }

        }
    }

    public function getMissingRequirements(): array
    {
        return $this->missings;
    }

    public function hasApplicationRequirements(): bool
    {
        return (bool) count($this->missings);
    }
}
