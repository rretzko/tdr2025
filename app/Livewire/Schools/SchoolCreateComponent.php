<?php

namespace App\Livewire\Schools;

use App\Models\PageView;
use App\Models\Schools\School;
use App\ValueObjects\SchoolResultsValueObject;
use Carbon\Carbon;
use Livewire\Component;

class SchoolCreateComponent extends Component
{
    public array $dto = [];
    public string $firstTimer = 'false';
    public string $header = '';
    public string $pageInstructions = '';
    public string $postalCode = '';
    public string $resultsPostalCode = '';

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
