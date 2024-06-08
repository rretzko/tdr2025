<?php

namespace App\Livewire\Forms;

use App\Models\EmergencyContact;
use App\Models\Students\Student;
use App\Services\FormatPhoneService;
use Livewire\Attributes\Validate;
use Livewire\Form;
use phpDocumentor\Reflection\Types\Void_;

class EmergencyContactForm extends Form
{
    #[Validate(['nullable', 'string'])]
    public string $email = '';
    #[Validate(['required', 'string'])]
    public string $name = '';
    #[Validate(['nullable', 'string'])]
    #[Validate('min:10', message: 'The home phone field must be at least 10 numeric characters.')]
    public string $phoneHome = '';
    #[Validate(['nullable', 'string'])]
    #[Validate('min:10', message: 'The cell phone field must be at least 10 numeric characters.')]
    public string $phoneMobile = '';
    #[Validate(['nullable', 'string'])]
    #[Validate('min:10', message: 'The work phone field must be at least 10 numeric characters.')]
    public string $phoneWork = '';
    public string $bestPhone = 'mobile';
    #[Validate(['required', 'min:1'])]
    public int $emergencyContactTypeId = 1;
    public Student $student;
    public string $sysId = 'new';

    public function setStudent(Student $student): void
    {
        $this->student = $student;
    }

    public function update(): void
    {
        $this->validate();

        $service = new FormatPhoneService();

        if ($this->sysId === 'new') {

            $this->add($service);
        } else {

            EmergencyContact::update(
                [
                    'id' => $this->sysId,
                    'student_id' => $this->student->id,
                ],
                [
                    'emergency_contact_type_id' => $this->emergencyContactTypeId,
                    'name' => $this->name,
                    'email' => $this->email,
                    'phoneHome' => $service->getPhoneNumber($this->phoneHome),
                    'phoneMobile' => $service->getPhoneNumber($this->phoneMobile),
                    'phoneWork' => $service->getPhoneNumber($this->phoneWork),
                    'bestPhone' => $this->bestPhone,
                ]
            );
        }
    }

    private function add(FormatPhoneService $service): void
    {
        EmergencyContact::create(
            [
                'student_id' => $this->student->id,
                'emergency_contact_type_id' => $this->emergencyContactTypeId,
                'name' => $this->name,
                'email' => $this->email,
                'phoneHome' => $service->getPhoneNumber($this->phoneHome),
                'phoneMobile' => $service->getPhoneNumber($this->phoneMobile),
                'phoneWork' => $service->getPhoneNumber($this->phoneWork),
                'bestPhone' => $this->bestPhone,
            ]
        );
    }
}
