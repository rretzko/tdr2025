<?php

namespace App\Imports;

use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\LibStack;
use App\Services\ConvertToPenniesService;
use App\Services\Libraries\CreateLibItemService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use stdClass;
use App\Enums\ItemType;

class LibraryItemsImport implements ToModel, WithHeadingRow
{
    public function __construct(private readonly string $libraryId)
    {
    }

    public function chunkSize(): int
    {
        return 250;
    }

    /**
     * array:15 [
     * 0 => type,
     * 1 => title,
     * 2 => voicing,
     * 3 => composer,
     * 4 => arranger,
     * 5 => words-and-music,
     * 6 => words,
     * 7 => music,
     * 8 => choreographer,
     * 9 => tags,
     * 10 => copies,
     * 11 => price,
     * 12 => location1,
     * 13 => location2,
     * 14 => location3,
     * ]
     * @param  array  $row
     * @return void
     */
    public function model(array $row)
    {
        //automatically skips the header row (i.e. WithHeadingRow)
        static $counter = 1;

        //extract $row data into the LibItemForm object
        $form = $this->makeForm($row);
        Log::info('title: '.$form->title);
        //1. Create a new item
        $service = new CreateLibItemService($form, $form->tags, $form->locations, $this->libraryId);
        $libItemId = $service->libItemId;

        //2. Add item to libStacks
        $this->addItemToLibraryStacks($libItemId, $form->count, $form->price);

        $counter++;
        Log::info('libItem: counter: '.$counter);
        Log::info('libItem: id: '.$libItemId);

    }

    private function addItemToLibraryStacks(int $libItemId, int|null $count, float|null $price): void
    {
        LibStack::updateOrCreate(
            [
                'library_id' => $this->libraryId,
                'lib_item_id' => $libItemId,
            ],
            [
                'count' => $count ?? 1,
                'price' => is_null($price) ? 0 : ConvertToPenniesService::usdToPennies($price),
            ]
        );
    }

    private function cleanCopies(int|float|string|null $copies): int
    {
        return (is_int($copies) && $copies > 0) ? $copies : 1;
    }

    private function cleanPrice(int|float|string|null $price): int|float
    {
        if (is_float($price)) {
            return $price;
        }

        if (is_int($price)) {
            return $price;
        }

        return 0;
    }

    private function cleanType(string|null $type): string
    {
        if (!$type) {
            return 'sheet music';
        }

        $lcType = Str::lower($type);

        $enumCase = ItemType::tryFrom($lcType);

        return ($enumCase !== null)
            ? $enumCase->value
            : 'sheet music'; //default
    }

    private function findVoicingId(string|null $voicingDescr): int
    {
        if ((!$voicingDescr) || is_null($voicingDescr)) { //set default
            $voicingDescr = 'satb';
        }

        return Voicing::firstOrCreate(
            [
                'category' => 'choral',
                'descr' => $voicingDescr,
            ],
            [
                'created_by' => auth()->id(),
            ]
        )->id;
    }

    private function makeForm($row): \stdClass
    {
        $form = new stdClass();
        $form->itemType = $this->cleanType($row['type']);
        $form->title = $row["title"];
        $form->voicingDescr = $row["voicing"];
        $form->voicingId = $this->findVoicingId($row['voicing']);
        $form->artists['composer'] = $row["composer"];
        $form->artists['arranger'] = $row["arranger"];
        $form->artists['wam'] = $row['words_and_music'];
        $form->artists['words'] = $row['words'];
        $form->artists['music'] = $row['music'];
        $form->artists['choreographer'] = $row['choreographer'];
        $form->tags = explode(",", $row["tags"]);
        $form->count = $this->cleanCopies($row['copies']);
        $form->price = $this->cleanPrice($row["price"]);
        $form->locations[] = $row["location1"];
        $form->locations[] = $row["location2"];
        $form->locations[] = $row["location3"];

        return $form;
    }
}
