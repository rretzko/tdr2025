<?php

namespace App\Livewire\Programs;

class ProgramCreateComponent extends ProgramsBasePage
{
    public function mount(): void
    {
        parent::mount();

        //set form defaults
        $this->form->sysId = 'new';
    }
    public function render()
    {
        return view('livewire..programs.program-create-component');
    }

    public function save(): void
    {
        $saved = $this->form->save();

        if ($saved) {
            $this->redirect(route('programs'));
        } else {
            $this->updateProgramExistsMessage();
        }
    }

    public function saveAndStay(): void
    {
        $saved = $this->form->save();

        if ($saved) {
            $this->form->resetVars();
            $this->successMessage = 'Program successfully created.';
        } else {
            $this->updateProgramExistsMessage();
        }
    }
}
