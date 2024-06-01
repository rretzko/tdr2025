<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentForm extends Form
{
    #[Validate('email', message: 'An email address is required.')]
    public string $email;
    #[Validate('required', message: 'First name is required.')]
    public string $first;
    #[Validate('required', message: 'Last name is required.')]
    public string $last;
    public string $middle;
    public string $suffix;
    public string $sysId = 'new';


    public function update(): void
    {
        $this->validate();
    }
}
