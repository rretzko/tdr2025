<?php

namespace App\Models\Ensembles\Inventories;

use App\Models\Ensembles\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'asset_id',
        'color',
        'comments',
        'item_id',
        'size',
        'status',
        'user_id',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
