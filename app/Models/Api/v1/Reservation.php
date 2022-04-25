<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'reservation_date',
        'reservation_time',
        'number_of_people',
        'phone_number',
        'confirmed',
        'order_number',
        'cancel_key',
        'token',
        'message'
    ];
    use HasFactory;
}
