<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationCapacity extends Model
{
    protected $fillable = [
        'daily_capacity',
        'table_capacity',
        'reservation_status',
        'min_time',
        'max_time'
    ];
    use HasFactory;
}
