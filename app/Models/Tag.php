<?php

namespace App\Models;

use App\Models\Libraries\Items\LibItem;
use App\Models\Programs\Program;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function libItems(): MorphToMany
    {
        return $this->morphedByMany(LibItem::class, 'taggable');
    }

    public function programs(): MorphToMany
    {
        return $this->morphedByMany(Program::class, 'taggable');
    }
}
