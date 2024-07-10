<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use App\Livewire\Forms\EventForm;


class EventCreateComponent extends BasePage
{
    const STATUSES = ['active', 'inactive', 'closed', 'sandbox'];
    public EventForm $form;

    public function render()
    {
        return view('livewire..events.event-create-component',
            [
                'statuses' => self::STATUSES,
                'maxRegistrantOptions' => range(0, 50),
                'ensembleCountOptions' => range(1, 10),
            ]);
    }

}
