<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\VersionDatesForm;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\UserConfig;
use Illuminate\Support\Str;

class VersionDatesEditComponent extends BasePage
{
    public VersionDatesForm $form;
    public Version $version;
    public int $versionId = 0;
    public array $successIndicators = [
        'adjudicationOpen' => false,
        'adjudicationClose' => false,
        'adminOpen' => false,
        'adminClose' => false,
        'finalTeacherChanges' => false,
        'membershipOpen' => false,
        'membershipClose' => false,
        'postmarkDeadline' => false,
        'participationFeeOpen' => false,
        'participationFeeClose' => false,
        'studentOpen' => false,
        'studentClose' => false,
        'studentCommitmentOpen' => false,
        'studentCommitmentClose' => false,
        'rehearsalOpen' => false,
        'rehearsalClose' => false,
        'tabRoomOpen' => false,
        'tabRoomClose' => false,
    ];

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($this->versionId);
        $this->form->setDates($this->versionId);
    }

    public function render()
    {
        return view('livewire..events.versions.version-dates-edit-component');
    }

    public function process()
    {
        $this->form->update($this->versionId);
    }

    public function updated($name, $value)
    {
        if (Str::contains($name, 'form.')) {

            $this->reset('successIndicators');

            //convert $name from (ex)form.adminClose to admin_close
            $suffix = Str::remove('form.', $name);
            $date_type = Str::snake($suffix);

            $this->form->updateDate($date_type, $value);

            $this->successIndicators[$suffix] = true;

            $this->successMessage = 'Date updated!';
        }


    }
}
