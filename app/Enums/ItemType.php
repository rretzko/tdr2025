<?php

namespace App\Enums;

enum ItemType: string
{
    case SheetMusic = 'sheet music';
    case Medley = 'medley';
    case Book = 'book';

    case Digital = 'digital';
    case CD = 'cd';
    case DVD = 'dvd';
    case Cassette = 'cassette';
    case Vinyl = 'vinyl';
}
