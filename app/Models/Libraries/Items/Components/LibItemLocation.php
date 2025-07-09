<?php

namespace App\Models\Libraries\Items\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibItemLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'lib_item_id',
        'location1',
        'location2',
        'location3',
    ];

    /**
     * return dash separated string of location1, location2, location3
     * @return string
     */
    public function getFormatLocationAttribute(): string
    {
        $locations = [$this->location1, $this->location2, $this->location3];
        array_filter($locations);
        return implode('-', $locations);
    }
}
