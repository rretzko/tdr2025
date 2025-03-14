<?php

namespace App\Livewire\Schools;

use App\Livewire\BasePage;
use App\Livewire\Forms\SchoolForm;
use App\Models\County;
use App\Models\PageView;
use App\Models\Schools\School;
use App\ValueObjects\SchoolResultsValueObject;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportRedirects\Redirector;

class SchoolCreateComponent extends BasePage
{
    public SchoolForm $form;
    public bool $emailVerified = false;
    public string $sysId = 'new';

    public function mount(): void
    {
        parent::mount();
        $this->header = 'Add '.ucwords($this->dto['header']);
    }

    public function render()
    {
        return view('livewire..schools.school-create-component',
            [
                'counties' => County::orderBy('name')->pluck('name', 'id')->toArray(),
            ]);
    }

    #[NoReturn] public function addSchool(int $schoolId): void
    {
        $this->form->setSchool(School::find($schoolId));
    }

    public function save()
    {
        $this->form->update();

        $this->successMessage = '"'.$this->form->name.'" has been saved to your Schools roster.';

        $this->showSuccessIndicator = true;

        return redirect()->route('schools')->with($this->successMessage);
    }

    public function updatedFormCity(): void
    {
        $this->form->updatedCity();
    }

    public function updatedFormCountyId(): void
    {
        $this->form->updatedCountyId();
    }

    public function updatedFormEmail(): void
    {
        dd(__LINE__);
        $this->form->updatedEmail();
    }

    public function updatedFormName(): void
    {
        $this->form->updatedName();
    }

    public function updatedFormPostalCode($value): void
    {
        $this->form->updatedPostalCode($value);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function pageInstructions(): string
    {
        return '<h3>Page Instructions</h3>';
    }
}
