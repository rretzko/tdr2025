<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\Components\LibItemRating;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibStack;
use App\Models\Schools\Teacher;
use App\Models\Tag;
use App\Services\ArtistIdService;
use App\Services\ArtistNameService;
use App\Services\ArtistSearchService;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\MakeAlphaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Traits\Libraries\LibrarySetLocationsTrait;

class LibraryItemForm extends Form
{
    use LibrarySetLocationsTrait;

    public array $artists = [
        'arranger' => '',
        'choreographer' => '',
        'composer' => '',
        'music' => '',
        'wam' => '',
        'words' => '',
    ];

    public array $artistIds = [
        'arranger' => 0,
        'choreographer' => 0,
        'composer' => 0,
        'music' => 0,
        'wam' => 0,
        'words' => 0,
    ];

    #[Validate('required')]
    public string $comments = 'adding item to library';
    public string $difficulty = 'medium';
    public string $level = 'high-school';

    public int $libraryId = 0;

    public array $locations = [];

    public string $itemType = 'sheet music';

    /**
     * ex: array:1 [▼ // app\Livewire\Forms\LibraryItemForm.php:53
     * "canEdit" => array:2 [▼
     * "itemType" => false
     * "title" => true
     * ]
     * ]
     */
    public array $policies = [
        'canEdit' => [
            'arranger' => true,
            'choreographer' => true,
            'composer' => true,
            'music' => true,
            'wam' => true,
            'words' => true,
            'voicing' => true,
        ],
    ];

    public int $rating = 1;
    public int $sysId = 0;
    public array $tags = [];
    public string $title = '';
    public string $voicingDescr = '';
    public int $voicingId = 0;

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
        $this->validate();

        $libItemId = ($this->sysId)
            ? $this->update($libraryId)
            : $this->add($libraryId, $itemTypes);

        $this->updateTags($libItemId);

        $this->updateLibItemLocations($libItemId);

        $this->updateLibItemRatings($libItemId);

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
        $this->artists = [
            'arranger' => '',
            'choreographer' => '',
            'composer' => '',
            'music' => '',
            'wam' => '',
            'words' => '',
        ];

        $this->artistIds = [
            'arranger' => 0,
            'choreographer' => 0,
            'composer' => 0,
            'music' => 0,
            'wam' => 0,
            'words' => 0,
        ];

        /**
         * ex: array:1 [▼ // app\Livewire\Forms\LibraryItemForm.php:53
         * "canEdit" => array:2 [▼
         * "itemType" => false
         * "title" => true
         * ]
         * ]
         */
        $this->policies = [
            'canEdit' => [
                'arranger' => true,
                'choreographer' => true,
                'composer' => true,
                'music' => true,
                'wam' => true,
                'words' => true,
                'voicing' => true,
            ],
        ];

        $this->itemType = 'sheet music';

        $this->sysId = 0;
        $this->title = '';

        $this->tags = [];

        $this->locations = ['', '', ''];

        $this->comments = 'adding item to library';
        $this->difficulty = 'easy';
        $this->rating = 1;
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
        $this->policies['canEdit']['arranger'] = $this->getPolicy('arranger', $libItem);
        $this->policies['canEdit']['composer'] = $this->getPolicy('composer', $libItem);
        $this->policies['canEdit']['words'] = $this->getPolicy('words', $libItem);

        //voicing
        $this->voicingId = $libItem->voicing_id;
        $this->voicingDescr = $libItem->voicingDescr;

        //artists
        $this->setArtists($libItem);

        //tags
        $this->setTags($libItem);

        //locations
        $this->setLocations($libItem);

        //ratings
        $this->setRatings($libItem);

    }

    private function add(int $libraryId, array $itemTypes): int
    {
        $service = new CreateLibItemService(
            $this,
            $itemTypes,
            $this->tags,
            $this->locations,
            $this->libraryId
        );

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

    /**
     * The ability to edit an artist's information is based on two criteria:
     *  1. The user created the Artist, and
     *  2. No other LibItem is dependent on the artist's information.
     * This policy is designed to restrict editing such that a single user cannot
     * impact information that is used by multiple users or across multiple objects.
     *
     * @param  LibItem  $libItem
     * @return bool
     */
    private function getPolicyArtist(string $type, LibItem $libItem): bool
    {
        $column = $type.'_id';
        $artist = Artist::find($libItem->$column);

        //early exit
        //if $artist, user can add an artist to the $libItem
        if (!$artist) {
            return true;
        }

        $artistId = $artist->id;

        $userCreatedObject = $artist->created_by == auth()->id();

        $occurrenceCount = LibItem::selectRaw("
                SUM(CASE WHEN composer_id = ? THEN 1 ELSE 0 END) +
                SUM(CASE WHEN arranger_id = ? THEN 1 ELSE 0 END) +
                SUM(CASE WHEN wam_id = ? THEN 1 ELSE 0 END) +
                SUM(CASE WHEN words_id = ? THEN 1 ELSE 0 END) +
                SUM(CASE WHEN music_id = ? THEN 1 ELSE 0 END) +
                SUM(CASE WHEN choreographer_id = ? THEN 1 ELSE 0 END) as total_count
            ", [$artistId, $artistId, $artistId, $artistId, $artistId, $artistId])->value('total_count');

        $userIsSoleDependent = ($occurrenceCount < 2);

        return ($userCreatedObject && $userIsSoleDependent);
    }

    private function getPolicyArranger(LibItem $libItem): bool
    {
        return $this->getPolicyArtist('arranger', $libItem);
    }

    private function getPolicyChoreographer(LibItem $libItem): bool
    {
        return $this->getPolicyArtist('choreographer', $libItem);
    }

    private function getPolicyComposer(LibItem $libItem): bool
    {
        return $this->getPolicyArtist('composer', $libItem);
    }

    private function getPolicyMusic(LibItem $libItem): bool
    {
        return $this->getPolicyArtist('music', $libItem);
    }

    private function getPolicyWam(LibItem $libItem): bool
    {
        return $this->getPolicyArtist('wam', $libItem);
    }

    private function getPolicyWords(LibItem $libItem): bool
    {
        return $this->getPolicyArtist('words', $libItem);
    }

    private function setArtists(LibItem $libItem): void
    {
        foreach ($this->artists as $artistType => $value) {

            $column = $artistType.'_id';

            if ($libItem->$column) {
                $artist = Artist::find($libItem->$column);
                $this->artists[$artistType] = $artist->artist_name;
                $this->artistIds[$artistType] = $artist->id;
                $this->policies['canEdit'][$artistType] = $this->getPolicy($artistType, $libItem);
            }
        }
    }

    private function setLocations(LibItem $libItem): void
    {
        $libItemLocation = LibItemLocation::query()
            ->where('library_id', $this->libraryId)
            ->where('lib_item_id', $libItem->id)
            ->first();

        if (!$libItemLocation) { //reset to default
            $this->locations = ['', '', ''];
        } else {
            $this->locations = [
                $libItemLocation->location1,
                $libItemLocation->location2,
                $libItemLocation->location3
            ];
        }
    }

    private function setRatings(LibItem $libItem): void
    {
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $libItemRating = LibItemRating::query()
            ->where('library_id', $this->libraryId)
            ->where('lib_item_id', $libItem->id)
            ->where('teacher_id', $teacherId)
            ->first();

        if ($libItemRating) {
            $this->comments = $libItemRating->comments;
            $this->difficulty = $libItemRating->difficulty;
            $this->rating = $libItemRating->rating;
        }
    }

    private function setTags(LibItem $libItem): void
    {
        foreach ($libItem->tags->sortBy('name') as $tag) {
            $this->tags[] = $tag->name;
        }
    }

    private function update(int $libraryId): int
    {
        $libItem = LibItem::find($this->sysId);
        $this->updateLibTitle($libItem, LibTitle::find($libItem->lib_title_id));
        $this->updateLibArtists($libItem);
        $this->updateVoicing($libItem);
        $this->updateLibItemRatings($libItem->id);

        return $libItem->id;
    }

    private function updateLibArtists(LibItem $libItem): void
    {
        $this->makeArtistIds();

        foreach ($this->artistIds as $type => $id) {

            if ($id) {
                $column = $type.'_id'; //ex. composer_id
                $libItem->$column = $id;
            }
        }

        $libItem->save();
    }

    private function updateLibItemLocations(int $libItemId): void
    {
        $this->setItemLocations($this->libraryId, $libItemId, $this->locations);
    }

    private function updateLibItemRatings(int $libItemId): void
    {
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;

        LibItemRating::updateOrCreate(
            [
                'library_id' => $this->libraryId,
                'lib_item_id' => $libItemId,
                'teacher_id' => $teacherId,
            ],
            [
                'rating' => $this->rating,
                'difficulty' => $this->difficulty,
                'comments' => $this->comments,
            ]
        );
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

    private function updateTags(int $libItemId): void
    {
        $libItem = LibItem::find($libItemId);

        if (!$libItem) {
            return;
        }

        $tagIds = [];

        foreach ($this->tags as $tag) {

            $tag = Tag::firstOrCreate([
                'name' => $tag,
            ]);

            $tagIds[] = $tag->id;
        }

        $libItem->tags()->syncWithoutDetaching($tagIds);
    }

    private function updateVoicing(LibItem $libItem): void
    {
        $voicingDescr = strtolower($this->voicingDescr);
        $voicing = Voicing::where('descr', $voicingDescr)->first();

        if (!$voicing) {
            $voicing = Voicing::create([
                'category' => 'choral',
                'descr' => $voicingDescr,
                'created_by' => auth()->id(),
            ]);
        }

        if ($voicing) {
            $libItem->voicing_id = $voicing->id;
            $libItem->save();
        }
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
                if ($this->policies['canEdit'][$type]) {

                    $artist = Artist::find($this->artistIds[$type]);

                    //update name fields
                    $service = new ArtistNameService($value);
                    $artist->update([
                        'artist_name' => $service->artistName,
                        'first_name' => $service->firstName,
                        'last_name' => $service->lastName,
                        'middle_name' => $service->middleName,
                        'alpha_name' => $service->alphaName,
                    ]);
                }

            }
        }
    }

}
