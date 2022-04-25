<?php

namespace App\Models\Api\v1\Time;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    protected $fillable = [
        'day_from',
        'day_to',
        'time_from',
        'time_to',
        'is_open'
    ];
    use HasFactory;
}
