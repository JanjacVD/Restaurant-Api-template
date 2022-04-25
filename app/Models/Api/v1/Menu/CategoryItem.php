<?php

namespace App\Models\Api\v1\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    protected $fillable = [
        'title_hr',
        'title_en',
        'section_id',
        'active'
    ];

    use HasFactory;
}
