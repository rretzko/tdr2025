<?php

namespace App\Models\Libraries\Items;

use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\LibStack;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'lib_subtitle_id',
        'lib_title_id',
    ];

    public function getTitleAttribute(): string
    {
        return LibTitle::find($this->lib_title_id)->title;
    }
}
