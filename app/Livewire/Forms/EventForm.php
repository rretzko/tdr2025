<?php

namespace App\Livewire\Forms;

use App\Models\Events\Event;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EventForm extends Form
{
    public int $ensembleCountId = 1;
    public string $grades = '';
    public
    string $logo = '';
    public int $maxRegistrants = 30;
    public int $maxUpperVoices = 0;
    public string $name = '';
    public string $orgName = '';
    public bool $requiredHeight = false;
    public bool $requiredShirtSize = false;
    public string $shortName = '';
    public int $statusId = 3; //default=sandbox
    public string $sysId = 'new';

    //ensemble values
    public array $ensembles = [];

    public function add(): Event
    {
        return Event::create(
            [
                'audition_count' => 1, //default
                'created_by' => auth()->id(),
                'ensemble_count' => $this->ensembleCountId,
                'frequency' => 'annual', //default
                'grades' => $this->cleanGrades(),
                'logo_file' => $this->logo,
                'logo_file_alt' => 'logo for '.$this->name,
                'max_registrant_count' => $this->maxRegistrants,
                'max_upper_voice_count' => $this->maxUpperVoices,
                'name' => $this->name,
                'organization' => $this->orgName,
                'short_name' => $this->shortName,
                'status' => $this->statusId,
                'required_height' => $this->requiredHeight,
                'required_shirt_size' => $this->requiredShirtSize,
            ]
        );
    }

    public function setEvent(int $id, array $statuses): void
    {
        $event = Event::find($id);

        $this->sysId = $id;
        $this->ensembleCountId = $event->ensemble_count;
        $this->grades = $event->grades;
        $this->logo = $event->logo_file;
        $this->maxRegistrants = $event->max_registrant_count;
        $this->maxUpperVoices = $event->max_upper_voice_count;
        $this->name = $event->name;
        $this->orgName = $event->organization;
        $this->requiredHeight = $event->required_height;
        $this->requiredShirtSize = $event->required_shirt_size;
        $this->shortName = $event->short_name;
        $this->statusId = array_flip($statuses)[$event->status];
    }

    /**
     * Ensure comma-separated string without white-space padding
     * @return string
     */
    private function cleanGrades(): string
    {
        $parts = explode(',', $this->grades);
        $trimmedParts = array_map('trim', $parts);
        return implode(',', $trimmedParts);
    }

}
