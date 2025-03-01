<?php

namespace App\Models\Libraries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibStack extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'library_id',
            'lib_item_id',
        ];
}
