<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibStack;
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

    public function save(int $libraryId): bool
    {
        $libItemId = ($this->sysId)
            ? $this->update($libraryId)
            : $this->add($libraryId);

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
    }

    private function add(int $libraryId): int
    {
        $libTitleId = $this->getLibTitleId();
        return $this->getLibItemId($libTitleId);
    }

    private function getLibItemId(int $libTitleId): int
    {
        if (LibItem::where('lib_title_id', $libTitleId)->exists()) {
            return LibItem::where('lib_title_id', $libTitleId)
                ->first()
                ->id;
        }

        return LibItem::create(
            [
                'item_type' => $this->itemType,
                'lib_title_id' => $libTitleId,
            ]
        )->id;

    }

    private function getLibTitleId(): int
    {
        if (LibTitle::where('title', $this->title)->exists()) {
            return LibTitle::where('title', $this->title)->first()->id;
        }

        //format title
        $fTitle = Str::title(trim($this->title));

        return LibTitle::create(
            [
                'teacher_id' => auth()->id(),
                'title' => $fTitle,
                'alpha' => MakeAlphaService::alphabetize($fTitle)
            ]
        )->id;
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

    private function update(int $libraryId): int
    {
        $libItem = LibItem::find($this->sysId);
        $updatedLibTitle = $this->updateLibTitle($libItem, LibTitle::find($libItem->lib_title_id));

        return $libItem->id;
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


}
