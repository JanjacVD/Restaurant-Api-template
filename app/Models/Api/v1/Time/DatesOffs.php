<?php

namespace App\Models\Api\v1\Time;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatesOffs extends Model
{
    protected $fillable = [
        'date_off'
    ];
    use HasFactory;
}
