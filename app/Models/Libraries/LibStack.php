<?php

namespace App\Models\Libraries;

use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\Items\LibItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LibStack extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'count',
            'library_id',
            'lib_item_id',
        ];

    public function getVoicingsArrayAttribute(): array
    {

        $voicingIdsAll = $this->libItems()
            ->pluck('voicing_id')
            ->filter() //remove any nulls
            ->toArray();

        $voicingIds = array_unique($voicingIdsAll);

        return Voicing::find($voicingIds)
            ->sortBy('descr')
            ->pluck('descr', 'id')
            ->toArray();
    }

    public function libItems(): Collection
    {
        $libItemIds = LibStack::query()
            ->where('library_id', $this->library_id)
            ->pluck('lib_item_id')
            ->toArray();

        return LibItem::find($libItemIds);
    }
}
