<?php

namespace App\Models\Programs\HonorChoirs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcOrganization extends Model
{
    /** @use HasFactory<\Database\Factories\Programs\HonorChoirs\HcOrganizationFactory> */
    use HasFactory;

    protected $fillable = ['name'];
}
