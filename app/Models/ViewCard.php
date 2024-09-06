<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewCard extends Model
{
    use HasFactory;

    protected $fillable = ['color', 'description', 'header', 'heroicon', 'href', 'label', 'order_by', 'role'];
}
