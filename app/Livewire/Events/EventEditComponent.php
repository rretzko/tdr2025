<?php

namespace App\Livewire\Events;

use App\Livewire\Forms\EventForm;
use App\Models\Events\Event;
use App\Models\Students\VoicePart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class EventEditComponent extends EventBasePage
{
    public bool $showErrorIndicator = false;

    public function mount(): void
    {
        parent::mount();

        $this->awsBucket = self::AWSBUCKET.
            $this->form->setEvent($this->dto['id'], self::STATUSES);
        $this->setLogo($this->dto['id']);
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

    public function save()
    {
        $this->reset('showSuccessIndicator');

        $this->form->update(self::STATUSES);

        $this->successMessage = 'Event successfully updated at: '.Carbon::now()->format('M d, Y h:m:s a').'.';

        $this->showSuccessIndicator = true;

        return back();
    }

    public function saveEnsemble(int $id)
    {
        $this->reset('showErrorIndicator');

        $this->showSuccessIndicator = $this->form->updateEventEnsemble($id);

        $this->showErrorIndicator = $this->form->showErrors;
    }

    private function setLogo(int $eventId): void
    {
        $event = Event::find($eventId);

        if ($event) {

            $this->logo = $event->logo_file;
        }
    }
}
