<?php

namespace App\Models\Api\v1\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    protected $fillable = [
        'title_hr',
        'title_en',
        'desc_hr',
        'desc_en',
        'price',
        'category_id',
        'image_name',
        'active'
    ];

    use HasFactory;
}
