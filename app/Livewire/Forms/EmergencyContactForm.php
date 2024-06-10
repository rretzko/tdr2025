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
    public EmergencyContact $emergencyContact;
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

    public function setEmergencyContact(EmergencyContact $emergencyContact)
    {
        $this->sysId = $emergencyContact->id;
        $this->email = $emergencyContact->email;
        $this->name = $emergencyContact->name;
        $this->phoneHome = $emergencyContact->phone_home;
        $this->phoneMobile = $emergencyContact->phone_mobile;
        $this->phoneWork = $emergencyContact->phone_work;
        $this->emergencyContactTypeId = $emergencyContact->emergency_contact_type_id;
        $this->bestPhone = $emergencyContact->best_phone;
        $this->student = Student::find($emergencyContact->student_id);
    }

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

            $emergencyContact = EmergencyContact::find($this->sysId);

            $emergencyContact->update(
                [
                    'emergency_contact_type_id' => $this->emergencyContactTypeId,
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone_home' => $service->getPhoneNumber($this->phoneHome),
                    'phone_mobile' => $service->getPhoneNumber($this->phoneMobile),
                    'phone_work' => $service->getPhoneNumber($this->phoneWork),
                    'best_phone' => $this->bestPhone,
                ]
            );

            $this->resetEmergencyContact();
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
                'phone_home' => $service->getPhoneNumber($this->phoneHome),
                'phone_mobile' => $service->getPhoneNumber($this->phoneMobile),
                'phone_work' => $service->getPhoneNumber($this->phoneWork),
                'best_phone' => $this->bestPhone,
            ]
        );

        $this->resetEmergencyContact();
    }

    private function resetEmergencyContact(): void
    {
        $this->reset('sysId', 'emergencyContactTypeId', 'name', 'email',
            'phoneHome', 'phoneMobile', 'phoneWork', 'bestPhone');
    }
}
