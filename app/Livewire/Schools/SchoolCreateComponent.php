<?php

namespace App\Livewire\Schools;

use App\Models\County;
use App\Models\PageView;
use App\Models\Schools\School;
use App\ValueObjects\SchoolResultsValueObject;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

class SchoolCreateComponent extends Component
{
    public string $advisoryCountyId = '';
    public string $city = '';
    public int $countyId = 0;
    public array $dto = [];
    public string $email = '';
    public bool $emailVerified = false;
    public string $firstTimer = 'false';
    public array $gradesITeach = [];
    public array $gradesTaught = [];
    public string $header = '';
    public string $pageInstructions = '';
    public string $name = '';
    public string $postalCode = '';
    public string $resultsCity = '';
    public string $resultsPostalCode = '';
    public string $resultsName = '';
    public string $successMessage = '';
    public bool $showSuccessIndicator = false;

    public function mount()
    {
        $this->header = 'Add '.ucwords($this->dto['header']);
        $this->pageInstructions = $this->dto['pageInstructions'];
        $this->setFirstTimer();
    }

    public function render()
    {
        return view('livewire..schools.school-create-component',
            [
                'pageInstructions' => $this->pageInstructions(),
                'counties' => County::orderBy('name')->pluck('name', 'id')->toArray(),
            ]);
    }

    #[NoReturn] public function addSchool(int $schoolId): void
    {
        dd($schoolId);
    }

    #[NoReturn] public function save(): void
    {
        $this->successMessage = '"'.$this->name.'" has been saved to your Schools roster.';

        $this->showSuccessIndicator = true;
    }

    public function updatedCity(): void
    {
        $this->reset('resultsCity');
        $min = 5; //minimum number of characters needed to initiate search

        $str = '<div>No schools found in city "'.$this->city.'".</div>';

        if (strlen($this->city) > $min) {

            $schools = School::query()
                ->where('city', 'LIKE', '%'.$this->city.'%')
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

        $this->resultsCity = (strlen($this->city) && strlen($this->city) < $min)
            ? 'Please enter at least '.$min.' characters'
            : $str;
    }

    public function updatedCountyId(): void
    {
        $this->advisoryCountyId = (County::find($this->countyId)->name === 'Unknown')
            ? 'An "unknown" county may preclude your engagement in and knowledge of some events.'
            : '';
    }

    public function updatedName(): void
    {
        $this->reset('resultsName');
        $min = 5; //minimum number of characters needed to initiate search

        $str = '<div>No schools found for "'.$this->name.'".</div>';

        if (strlen($this->name) > $min) {

            $schools = School::query()
                ->where('name', 'LIKE', '%'.$this->name.'%')
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

        $this->resultsName = (strlen($this->name) && strlen($this->name) < $min)
            ? 'Please enter at least '.$min.' characters'
            : $str;
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

    /** END OF PUBLIC FUNCTIONS **************************************************/

    /**
     * $firstTimer controls the default display of page instructions.
     * if the user is seeing the page for the first time, page instructions is displayed ('hide' option is displayed)
     * else, page instructions are hidden ('show' option is displayed)
     * @return void
     */
    private function setFirstTimer(): void
    {
        $this->firstTimer = 'true';
        $pageView = PageView::firstOrCreate(
            [
                'header' => $this->header,
                'user_id' => auth()->id(),
            ],
            [
                'view_count' => 0,
            ]
        );

        $this->firstTimer = ($pageView->view_count) ? 'false' : 'true';

        $pageView->update([
            'view_count' => ($pageView->view_count + 1)
        ]);
    }

    private function pageInstructions(): string
    {
        return '<h3>Page Instructions</h3>';
    }
}
