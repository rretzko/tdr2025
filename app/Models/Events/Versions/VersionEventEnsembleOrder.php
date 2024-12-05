<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Model;

class VersionEventEnsembleOrder extends Model
{
    protected $fillable = ['event_ensemble_id', 'order_by', 'version_id'];
}
