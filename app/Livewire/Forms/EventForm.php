<?php

namespace App\Livewire\Forms;

use App\Models\Events\Event;
use App\Models\Events\EventEnsemble;
use App\Models\Events\EventManagement;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EventForm extends Form
{
    public int $ensembleCountId = 1;
    public string $grades = '';
    public string $logoFile = '';
    public int $maxRegistrants = 30;
    public int $maxUpperVoices = 0;
    #[Validate('required|min:3')]
    public string $name = '';
    public string $orgName = '';
    public bool $requiredHeight = false;
    public bool $requiredShirtSize = false;
    #[Validate('required|min:3')]
    public string $shortName = '';
    public int $statusId = 3; //default=sandbox
    public string $sysId = 'new';

    //ensemble values
    public array $ensembles = [];
    public string $errors = '';
    public bool $showErrors = false;

    public function add(array $statuses): Event
    {
        $event = Event::create(
            [
                'audition_count' => 1, //default
                'created_by' => auth()->id(),
                'ensemble_count' => $this->ensembleCountId,
                'frequency' => 'annual', //default
                'grades' => $this->cleanGrades(),
                'logo_file' => $this->logoFile,
                'logo_file_alt' => 'logo for '.$this->name,
                'max_registrant_count' => $this->maxRegistrants,
                'max_upper_voice_count' => $this->maxUpperVoices,
                'name' => $this->name,
                'organization' => $this->orgName,
                'short_name' => $this->shortName,
                'status' => $statuses[$this->statusId],
                'required_height' => $this->requiredHeight,
                'required_shirt_size' => $this->requiredShirtSize,
            ]
        );

        //add manager role for user
        EventManagement::create(
            [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'role' => 'manager',
            ]
        );

        return $event;
    }

    public function setEvent(int $id, array $statuses): void
    {
        $event = Event::find($id);

        $this->sysId = $id;
        $this->ensembleCountId = $event->ensemble_count;
        $this->grades = $event->grades;
        $this->logoFile = $event->logo_file;
        $this->maxRegistrants = $event->max_registrant_count;
        $this->maxUpperVoices = $event->max_upper_voice_count;
        $this->name = $event->name;
        $this->orgName = $event->organization;
        $this->requiredHeight = $event->required_height;
        $this->requiredShirtSize = $event->required_shirt_size;
        $this->shortName = $event->short_name;
        $this->statusId = array_flip($statuses)[$event->status];

        $this->setEnsembles();
    }

    public function update(array $statuses)
    {
        $this->validate();

        $event = Event::find($this->sysId);

        $event->update(
            [
                'created_by' => auth()->id(),
                'ensemble_count' => $this->ensembleCountId,
                'grades' => $this->cleanGrades(),
                'logo_file' => $this->logoFile,
                'logo_file_alt' => 'logo for '.$this->name,
                'max_registrant_count' => $this->maxRegistrants,
                'max_upper_voice_count' => $this->maxUpperVoices,
                'name' => $this->name,
                'organization' => $this->orgName,
                'short_name' => $this->shortName,
                'status' => $statuses[$this->statusId],
                'required_height' => $this->requiredHeight,
                'required_shirt_size' => $this->requiredShirtSize,
            ]
        );
    }

    public function updateEventEnsemble(int $id): bool
    {
        $this->reset('errors', 'showErrors');

        $inputs = $this->ensembles[$id];

        $this->validateInputs($inputs);

        if (!$this->showErrors) {

            if ($inputs['id'] === 'new') {

                EventEnsemble::create(
                    [
                        'event_id' => $this->sysId,
                        'ensemble_name' => $inputs['name'],
                        'ensemble_short_name' => $inputs['shortName'],
                        'grades' => implode(',', $inputs['grades']),
                        'voice_part_ids' => implode(',', $inputs['voiceParts']),
                    ]
                );
            } else {

                $ensemble = EventEnsemble::find($inputs['id']);

                $ensemble->update(
                    [
                        'ensemble_name' => $inputs['name'],
                        'ensemble_short_name' => $inputs['shortName'],
                        'grades' => implode(',', $inputs['grades']),
                        'voice_part_ids' => implode(',', $inputs['voiceParts']),
                    ]
                );
            }

            if (array_key_exists($id, $this->ensembles)) {
                $this->ensembles[$id]['showSuccessIndicator'] = true;
                $this->ensembles[$id]['successMessage'] = 'Ensemble #'.($id + 1).' successfully updated.';
            }

            return true;
        }

        return false;
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

    private function setEnsembles()
    {
        //build empty ensembles array structure
        for ($i = 0; $i < $this->ensembleCountId; $i++) {

            $this->ensembles[$i] =
                [
                    'id' => 'new',
                    'name' => '',
                    'shortName' => '',
                    'grades' => [],
                    'voiceParts' => [],
                    'showSuccessIndicator' => false,
                    'successMessage' => '',
                ];
        }

        //fill array structure with concrete model if available
        $ensembles = EventEnsemble::where('event_id', $this->sysId)->get();

        foreach ($ensembles as $key => $ensemble) {

            $this->ensembles[$key] =
                [
                    'id' => $ensemble->id,
                    'name' => $ensemble->ensemble_name,
                    'shortName' => $ensemble->ensemble_short_name,
                    'grades' => explode(',', $ensemble->grades),
                    'voiceParts' => explode(',', $ensemble->voice_part_ids),
                    'showSuccessIndicator' => false,
                    'successMessage' => '',
                ];
        }
    }

    private function validateInputs(array $inputs): void
    {
        if (!strlen($inputs['name'])) {

            $this->errors .= '<div>Ensemble name is a required field.</div>';
        }

        if (!strlen($inputs['shortName'])) {

            $this->errors .= '<div>Ensemble short name is a required field.</div>';
        }

        if (strlen($this->errors)) {

            $this->showErrors = true;
        }
    }

}
