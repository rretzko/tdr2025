<?php

namespace App\Livewire\Programs;

class ProgramEditComponent extends ProgramsBasePage
{
    public function mount(): void
    {
        parent::mount();

        $this->form->sysId = $this->dto['programId'];
    }

    public function render()
    {
        return view('livewire..programs.program-edit-component');
    }

    public function save(): void
    {
        $saved = $this->form->update();

        if ($saved) {
            $this->redirect(route('programs'));
        } else {
            $this->updateProgramExistsMessage();
        }
    }

    public function saveAndStay(): void
    {
        $saved = $this->form->update();

        if ($saved) {
            $this->form->resetVars();
            $this->successMessage = 'Program successfully updated.';
        } else {
            $this->updateProgramExistsMessage();
        }
    }
}
