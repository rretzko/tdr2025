<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibStack;
use App\Services\ArtistIdService;
use App\Services\ArtistSearchService;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\MakeAlphaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LibraryItemForm extends Form
{
    public array $artists = [
        'arranger' => '',
        'composer' => '',
        'words' => '',
    ];

    public array $artistIds = [
        'arranger' => 0,
        'composer' => 0,
        'words' => 0,
    ];
    public string $itemType = 'sheet music';

    /**
     * ex: array:1 [▼ // app\Livewire\Forms\LibraryItemForm.php:53
     * "canEdit" => array:2 [▼
     * "itemType" => false
     * "title" => true
     * ]
     * ]
     */
    public array $policies = [];
    public int $sysId = 0;
    public string $title = '';

    /**
     * Translate $this->itemType into blade file name counterpart
     */
    public function itemTypeBlade(): string
    {
        $xlats = [
            'sheet music' => 'sheetMusic',
        ];

        return (array_key_exists($this->itemType, $xlats))
            ? $xlats[$this->itemType]
            : $this->itemType;
    }

    public function save(int $libraryId, array $itemTypes): bool
    {
        $libItemId = ($this->sysId)
            ? $this->update($libraryId)
            : $this->add($libraryId, $itemTypes);

        return (bool)LibStack::updateOrCreate(
            [
                'library_id' => $libraryId,
                'lib_item_id' => $libItemId,
            ],
            []
        );
    }

    public function resetVars(): void
    {
        $this->artists = [];
        $this->artistIds = [];
        $this->itemType = 'sheet music';
        $this->policies = [];
        $this->sysId = 0;
        $this->title = '';
    }

    public function setLibItem(LibItem $libItem): void
    {
        $this->sysId = $libItem->id;

        //item type
        $this->itemType = $libItem->item_type;
        $this->policies['canEdit']['itemType'] = false;

        //title
        $libTitle = LibTitle::find($libItem->lib_title_id);
        $this->title = $libTitle->title;
        $this->policies['canEdit']['title'] = $this->getPolicy('canEdit', $libTitle);

        //artists
        $this->setArtists($libItem);
    }

    private function add(int $libraryId, array $itemTypes): int
    {
        $service = new CreateLibItemService($this, $itemTypes);

        return ($service)
            ? $service->libItemId
            : 0;
    }

    /**
     * Determine if user can act on a specific item component
     * @param string $type //ex: canEdit
     * @param Model $object //ex: libTitle
     * @return bool
     */
    private function getPolicy(string $type, Model $object): bool
    {
        $method = 'getPolicy' . ucwords($type);
        return $this->$method($object);
    }

    private function getPolicyCanEdit(Model $object): bool
    {
        $className = class_basename($object);
        $foreignKey = Str::snake($className) . '_id';

        $userCreatedObject = ($object->teacher_id == auth()->id());
        $userIsSoleDependent = (LibItem::where($foreignKey, $object->id)->count('id') < 2);

        return ($userCreatedObject && $userIsSoleDependent);
    }

    private function setArtists(LibItem $libItem): void
    {
        if ($libItem->composer_id) {
            $composer = Artist::find($libItem->composer_id);
            $this->artists['composer'] = $composer->artist_name;
            $this->artistIds['composer'] = $composer->id;
        }

        if ($libItem->arranger_id) {
            $arranger = Artist::find($libItem->arranger_id);
            $this->artists['arranger'] = $arranger->artist_name;
            $this->artistIds['arranger'] = $arranger->id;
        }

    }

    private function update(int $libraryId): int
    {
        $libItem = LibItem::find($this->sysId);
        $updatedLibTitle = $this->updateLibTitle($libItem, LibTitle::find($libItem->lib_title_id));
        $updatedLibArtists = $this->updateLibArtists($libItem);

        return $libItem->id;
    }

    private function updateLibArtists(LibItem $libItem): bool
    {
        $this->makeArtistIds();

        foreach ($this->artistIds as $type => $id) {

            if ($id) {
                $column = $type.'_id';
                $libItem->$column = $id;
            }
        }

        return $libItem->save();

        //early exit
//        if ($libTitle->title === $this->title) {
//            return true;
//        }
//
//        $newLibTitleId = (LibTitle::where('title', $this->title)->exists())
//            ? LibTitle::where('title', $this->title)->first()->id
//            : LibTitle::create(
//                [
//                    'teacher_id' => auth()->id(),
//                    'title' => $this->title,
//                    'alpha' => MakeAlphaService::alphabetize($this->title),
//                ]
//            )->id;
//
//        return $libItem->update(
//            [
//                'lib_title_id' => $newLibTitleId,
//            ]
//        );
    }

    private function updateLibTitle(LibItem $libItem, LibTitle $libTitle): bool
    {
        //early exit
        if ($libTitle->title === $this->title) {
            return true;
        }

        $newLibTitleId = (LibTitle::where('title', $this->title)->exists())
            ? LibTitle::where('title', $this->title)->first()->id
            : LibTitle::create(
                [
                    'teacher_id' => auth()->id(),
                    'title' => $this->title,
                    'alpha' => MakeAlphaService::alphabetize($this->title),
                ]
            )->id;

        return $libItem->update(
            [
                'lib_title_id' => $newLibTitleId,
            ]
        );
    }

    private function makeArtistIds(): void
    {
        if (empty($this->artists)) {
            return;
        }

        $this->artistIds = $this->artistIds ?? [];

        foreach ($this->artists as $type => $value) {

            //ensure:
            //  1. $value is not an empty string
            //  2. The user hasn't selected an existing artist from search results
            if (strlen($value) && (!$this->artistIds[$type])) {

                $service = new ArtistIdService($value);
                $this->artistIds[$type] = $service->getId();
            }

            //if use is correcting the artist's name:
            //  1. check can-edit
            //  2. update name fields
            if (strlen($value) && $this->artistIds[$type]) {

                //check can-edit
                /** @todo build policy */
                //update name fields
                /**************** PICK UP DEVELOPMENT HERE ****************************/
//                new ArtistNameService(Artist::find($this->artisIds[$type], $value));
            }
        }
    }


}
