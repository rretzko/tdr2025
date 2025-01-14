<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\Forms\VersionProfileForm;
use App\Mail\VersionStatusChangedMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VersionProfileComponent extends BasePageVersion
{
    public VersionProfileForm $form;

    public function mount(): void
    {
        parent::mount();

        if ($this->dto['id']) {

            $this->form->setProfile($this->dto['id']);
        } else {

            if ($this->form->setProfileClone()) {

                $this->showSuccessIndicator = true;
            }
        }

        $this->form->setSeniorClassId();
    }

    public function render()
    {
        return view('livewire..events.versions.version-profile-component',
            [
                'seniorClasses' => $this->getSeniorClasses(),
                'statuses' => self::STATUSES,
            ]);
    }

    public function save()
    {
        $version = $this->form->update($this->dto['id']);

        $this->showSuccessIndicator = true;

        $this->successMessage = 'The version has been added.';

        $this->storeVersionId($version);

        return redirect()->route('version.show', ['version' => $version]);
    }

    public function updatedFormStatusId(): void
    {
        $founder = User::find(config('app.founderId'));
        $email = $founder->email;

        //send email for versions which are NOT being created
        if ($this->form->sysId !== 'new') {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($founder)->send(new VersionStatusChangedMail($this->form->statusId));

            } else {
                Log::error("Invalid email address: $email");
            }
        }

    }
}
