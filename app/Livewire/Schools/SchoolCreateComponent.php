<?php

namespace App\Livewire\Schools;

use Livewire\Component;

class SchoolCreateComponent extends Component
{
    public array $dto = [];
    public string $header = '';
    public string $pageInstructions = '';

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

    private function pageInstructions(): string
    {
        return '<h3>Page Instructions</h3>';
    }
}
