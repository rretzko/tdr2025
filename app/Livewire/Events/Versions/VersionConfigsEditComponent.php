<?php

namespace App\Livewire\Events\Versions;


use App\Livewire\Forms\VersionConfigsForm;
use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;

class VersionConfigsEditComponent extends BasePageVersion
{
    public VersionConfigsForm $form;
    public string $selectedTab = 'advisory'; //'adjudication';
    public Event $event;
    public Version $version;

    public function mount(): void
    {
        parent::mount();
        $this->event = Event::find(UserConfig::getValue('eventId'));
        $this->version = Version::find(UserConfig::getValue('versionId'));
        //assign form based on $selectedTab value
        $this->updatedSelectedTab();
    }

    public function render()
    {
        return view('livewire..events.versions.version-configs-edit-component',
            [
                'tabs' => self::TABS,
                'count1thru5Options' => [1 => 1, 2, 3, 4, 5],
            ]);
    }

    public function process(): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        //updateAdjudication, updateRegistrants
        $updateMethod = 'update'.ucfirst($this->selectedTab);

        $this->form->$updateMethod($this->version->id);

        $this->showSuccessIndicator = true;

        $this->successMessage = 'This configuration has been updated.';
    }

    public function remove()
    {

    }

    public function updatedSelectedTab(): void
    {
        //setRowAdjudication, setRowRegistrants, etc.
        $method = 'setRow'.ucfirst($this->selectedTab);

        $this->form->$method($this->version->id);
    }
}
