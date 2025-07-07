<?php

namespace App\Livewire\Libraries;

use App\Livewire\BasePage;
use App\Models\Libraries\Items\LibItem;
use Barryvdh\DomPDF\Facade\Pdf;

class LibraryBasePage extends BasePage
{
    public array $itemsToPull = [];

    public function downloadPullSheetPdf()
    {
        $itemIds = implode(',', $this->itemsToPull);
        $this->reset('itemsToPull');
        $this->redirectRoute('pdf.pullSheet', ['itemIds' => $itemIds]);
    }

}
