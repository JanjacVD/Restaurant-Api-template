<?php

namespace App\Models\Api\v1\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionItem extends Model
{
    protected $fillable = [
        'title_hr',
        'title_en',
        'active'
    ];

    use HasFactory;
}
