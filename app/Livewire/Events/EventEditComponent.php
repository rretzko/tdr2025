<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use App\Livewire\Forms\EventForm;
use App\Models\Students\VoicePart;

class EventEditComponent extends BasePage
{
    const STATUSES = ['active', 'inactive', 'closed', 'sandbox'];
    public EventForm $form;

    public function mount(): void
    {
        parent::mount();

        $this->form->setEvent($this->dto);
    }

    public function render()
    {
        return view('livewire.events.event-edit-component',
            [
                'statuses' => self::STATUSES,
                'maxRegistrantOptions' => range(0, 50),
                'ensembleCountOptions' => [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                'voiceParts' => VoicePart::orderBy('order_by')->pluck('descr', 'id')->toArray(),
            ]);
    }
}
