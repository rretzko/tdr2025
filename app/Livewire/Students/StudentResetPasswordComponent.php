<?php

namespace App\Livewire\Students;

use App\Livewire\Students\BasePageStudent;
use Illuminate\Support\Facades\Hash;

class StudentResetPasswordComponent extends BasePageStudent
{
    public string $lcEmail = ''; //lower-case email address

    public function mount(): void
    {
        parent::mount();

        $this->lcEmail = strtolower($this->student->user->email);

        $this->selectedTab = 'reset password';
    }

    public function render()
    {
        return view('livewire..students.student-reset-password-component');
    }

    public function resetPassword(): void
    {
        $this->student->user->update(
            [
                'password' => Hash::make($this->lcEmail),
            ]
        );

        $this->showSuccessIndicator = true;
        $this->successMessage = "The student's password has been updated.";
    }
}
