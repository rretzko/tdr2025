<?php

namespace App\Livewire\Students;

use App\Livewire\Students\BasePageStudent;
use App\Models\Address;
use App\Models\PhoneNumber;
use App\Models\User;

class StudentCommsEditComponent extends BasePageStudent
{
    public string $successMessageAddress = '';
    public string $successMessageEmail = '';
    public string $successMessagePhones = '';

    public function mount(): void
    {
        parent::mount();

        $this->selectedTab = 'comms';
    }

    public function render()
    {
        return view('livewire..students.student-comms-edit-component',
            [
                'geostates' => $this->getGeostates(),
            ]);
    }

    public function messages(): array
    {
        return [
            'form.email.required' => 'An email is required.',
            'form.email.email' => 'The email is improperly formatted.',
            'form.email.unique' => 'The email has already been taken.',
            'form.postalCode.min' => 'The zip code must be at least 5 characters.'
        ];
    }

    public function updatedFormAddress1(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate(
            [
                'form.address1' => ['nullable', 'string'],
            ]
        );

        Address::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
            ],
            [
                'address1' => $this->form->address1,
            ]
        );

        $this->successMessageAddress = 'Address-1 updated.';
    }

    public function updatedFormAddress2(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate(
            [
                'form.address2' => ['nullable', 'string'],
            ]
        );

        Address::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
            ],
            [
                'address2' => $this->form->address2,
            ]
        );

        $this->successMessageAddress = 'Address-2 updated.';
    }

    public function updatedFormCity(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate(
            [
                'form.city' => ['nullable', 'string'],
            ]
        );

        Address::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
            ],
            [
                'city' => $this->form->city,
            ]
        );

        $this->successMessageAddress = 'City updated.';
    }

    public function updatedFormEmail(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate(
            [
                'form.email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            ]
        );

        $this->student->user->update(['email' => $this->form->email]);

        $this->successMessageEmail = 'Email updated.';
    }

    public function updatedFormGeostateId(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate(
            [
                'form.geostate_id' => ['required', 'exists:geostates,id'],
            ]
        );

        Address::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
            ],
            [
                'geostate_id' => $this->form->geostate_id,
            ]
        );

        $this->successMessageAddress = 'State updated.';
    }

    public function updatedFormPhoneHome(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate([
            'form.phoneHome' => ['nullable', 'string'],
        ]);

        PhoneNumber::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
                'phone_type' => 'home',
            ],
            [
                'phone_number' => $this->form->phoneHome,
            ],
        );

        $this->successMessagePhones = 'Home phone has been updated.';
    }

    public function updatedFormPhoneMobile(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate([
            'form.phoneMobile' => ['nullable', 'string'],
        ]);

        PhoneNumber::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
                'phone_type' => 'mobile',
            ],
            [
                'phone_number' => $this->form->phoneMobile,
            ],
        );

        $this->successMessagePhones = 'Cell phone has been updated.';
    }

    public function updatedFormPostalCode(): void
    {
        $this->reset('successMessageAddress', 'successMessageEmail', 'successMessagePhones');

        $this->validate(
            [
                'form.postalCode' => ['nullable', 'string', 'min:5'],
            ]
        );

        Address::updateOrCreate(
            [
                'user_id' => $this->student->user_id,
            ],
            [
                'postal_code' => $this->form->postalCode,
            ]
        );

        $this->successMessageAddress = 'Zip code updated.';
    }
}
