<?php

namespace App\Models\Libraries\Items\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibMedleySelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'lib_item_id',
        'lib_title_id',
        'teacher_id',
    ];

    public function getTitleAttribute(): string
    {
        return LibTitle::where('id', $this->lib_title_id)->first()->title;
    }
}
