<?php

namespace App\Livewire\Schools;

use App\Models\Schools\School;
use App\ValueObjects\SchoolResultsValueObject;
use Carbon\Carbon;
use Livewire\Component;

class SchoolCreateComponent extends Component
{
    public array $dto = [];
    public string $header = '';
    public string $pageInstructions = '';
    public string $postalCode = '';
    public string $resultsPostalCode = '';

    public function mount()
    {
        $this->header = 'Add '.ucwords($this->dto['header']);
        $this->pageInstructions = $this->dto['pageInstructions'];
    }

    public function render()
    {
        return view('livewire..schools.school-create-component',
            [
                'pageInstructions' => $this->pageInstructions(),
            ]);
    }

    public function addSchool(int $schoolId): void
    {
        dd($schoolId);
    }

    public function updatedPostalCode(): void
    {
        $this->reset('resultsPostalCode');

        $str = '<div>No schools found for "'.$this->postalCode.'".</div>';

        if (strlen($this->postalCode) > 3) {

            $schools = School::query()
                ->where('postal_code', 'LIKE', $this->postalCode.'%')
                ->orderBy('name')
                ->orderBy('city')
                ->get();

            if ($schools->count()) {

                $str = '';

                foreach ($schools as $school) {

                    $str .= '<button type="button" wire:click="addSchool('.$school->id.')" class="text-sm text-blue-500 ml-2">'
                        .SchoolResultsValueObject::getVo($school) //"schoolName (city in county, state)"
                        .'</button>';
                }
            }
        }

        $this->resultsPostalCode = $str;
    }

    private function pageInstructions(): string
    {
        return '<h3>Page Instructions</h3>';
    }
}
