<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentForm extends Form
{
    /**
     * @var string
     */
    #[Validate('nullable', 'date')]
    public string $birthday = '';
    #[Validate('required')]
    public int $classOf = 0;
    #[Validate('email', message: 'An email address is required.')]
    public string $email;
    #[Validate('required', message: 'First name is required.')]
    public string $first;
    #[Validate('required', 'min:30', 'max:80')]
    public int $heightInInches = 30; //minimum height
    #[Validate('required', message: 'Last name is required.')]
    public string $last;
    #[Validate('nullable', 'string')]
    public string $middle;
    #[Validate('nullable', 'string')]
    public string $phoneHome;
    #[Validate('nullable', 'string')]
    public string $phoneMobile;
    #[Validate('required', 'int', 'exists:pronouns,id')]
    public int $pronounId;
    #[Validate('required', 'string')]
    public int $shirtSize;
    #[Validate('nullable', 'string')]
    public string $suffix;
    public string $sysId = 'new';
    #[Validate('required', 'exists:voice_parts,id')]
    public int $voicePartId = 1; //default soprano

    public function setBirthday(): void
    {
        $this->birthday = Carbon::now()->subYears(18)->format('Y-m-d');
    }

    public function update(): void
    {
        $this->validate();
    }
}
