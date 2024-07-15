<?php

namespace App\Livewire\Events;

use App\Livewire\Forms\EventForm;
use App\Models\Students\VoicePart;

class EventCreateComponent extends EventBasePage
{
    public function render()
    {
        return view('livewire..events.event-create-component',
            [
                'statuses' => self::STATUSES,
                'maxRegistrantOptions' => range(0, 50),
                'ensembleCountOptions' => [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                'voiceParts' => VoicePart::orderBy('order_by')->pluck('descr', 'id')->toArray(),
            ]);
    }

    public function save()
    {
        $event = $this->form->add(self::STATUSES);

        return $this->redirectRoute('event.edit', [$event]);
    }

}
