<?php

namespace App\Livewire\Students;

use App\Livewire\Forms\EmergencyContactForm;
use App\Models\EmergencyContact;
use App\Models\Students\EmergencyContactType;

class StudentECEditComponent extends BasePageStudent
{
    public EmergencyContactForm $ecForm;

    public function mount(): void
    {
        parent::mount();

        $this->selectedTab = 'emergency contact';
        $this->ecForm->setStudent($this->student);
    }

    public function edit(EmergencyContact $emergencyContact)
    {
        $this->ecForm->setEmergencyContact($emergencyContact);
    }

    public function render()
    {
        return view('livewire..students.student-e-c-edit-component',
            [
                'emergencyContactTypes' => $this->getEmergencyContactTypes(),
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getEmergencyContacts(),
            ]);
    }

    public function remove(EmergencyContact $emergencyContact): void
    {
        $name = $emergencyContact->name;

        $emergencyContact->delete();

        $this->successMessage = 'Emergency Contact named: '.$name.' has been removed.';
    }

    public function save(): void
    {
        $this->ecForm->update();

        $this->successMessage = 'Emergency Contacts updated.';
    }

    private function getColumnHeaders(): array
    {
        return ['name', 'relationship', 'email', 'phones'];
    }

    private function getEmergencyContacts(): array
    {
        return EmergencyContact::query()
            ->where('student_id', $this->student->id)
            ->get()
            ->toArray();
    }

    private function getEmergencyContactTypes(): array
    {
        return EmergencyContactType::query()
            ->orderBy('order_by')
            ->pluck('relationship', 'id')
            ->toArray();
    }
}
