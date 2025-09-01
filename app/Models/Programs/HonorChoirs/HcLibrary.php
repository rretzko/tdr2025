<?php

namespace App\Models\Programs\HonorChoirs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcLibrary extends Model
{
    /** @use HasFactory<\Database\Factories\\App\Models\Programs\HonorChoirs\HcLibraryFactory> */
    use HasFactory;

    protected $fillable = [
      'hc_event_id',
      'title',
      'subtitle',
      'artist',
    ];
}
