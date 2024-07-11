<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use App\Livewire\Forms\EventForm;
use App\Models\Students\VoicePart;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportRedirects\Redirector;


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
                'ensembleCountOptions' => [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                'voiceParts' => VoicePart::orderBy('order_by')->pluck('descr', 'id')->toArray(),
            ]);
    }

    public function save()
    {
        $event = $this->form->add();

        return $this->redirectRoute('event.edit', [$event]);
    }

}
