<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use App\Livewire\Forms\EventForm;
use App\Models\Events\Event;
use App\Models\Students\VoicePart;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;


class EventBasePage extends BasePage
{
    use WithFileUploads;

    const STATUSES = ['active', 'inactive', 'closed', 'sandbox'];

    public string $awsBucket;
    public EventForm $form;
    #[Validate('image|max:1024')]
    public $logo;

    public function mount(): void
    {
        parent::mount();
    }

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

    public function updatedLogo(): void
    {
        $fileName = Str::camel($this->form->name);
        $extension = pathInfo($this->logo->getClientOriginalName(), PATHINFO_EXTENSION);
        $fullFileName = $fileName.'.'.$extension;

        $this->logo->storePubliclyAs('logos', $fullFileName, 's3');

        //save new logo if $this->form->sysId
        if ($this->form->sysId !== 'new') {

            $event = Event::find($this->form->sysId);
            $event->update(['logo_file' => 'logos/'.$fullFileName]);
        }

        $this->form->logoFile = 'logos/'.$fullFileName;
    }

}
