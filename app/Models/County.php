<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class County extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'geostate_id',
    ];

    public function geostate(): BelongsTo
    {
        return $this->belongsTo(Geostate::class);
    }
}
