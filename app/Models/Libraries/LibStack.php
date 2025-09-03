<?php

namespace App\Models\Libraries;

use App\Jobs\LibStackCreatedEmailJob;
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
            'price',
        ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {

            //count items in this libStack
            $libItemsCount = LibStack::where('library_id', $model->library_id)->count();

            //send advisory email on the first item created in a library
            if($libItemsCount === 1){
                LibStackCreatedEmailJob::dispatch($model);
            }
        });

    }

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
