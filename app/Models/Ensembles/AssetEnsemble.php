<?php

namespace App\Models\Ensembles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetEnsemble extends Model
{
    public $timestamps = false;

    protected $table = 'asset_ensemble';

    protected $fillable = [
        'ensemble_id',
        'asset_id',
    ];

    public function ensemble(): BelongsTo
    {
        return $this->belongsTo(Ensemble::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
