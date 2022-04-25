<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Menu\SectionItem;
use App\Models\Api\v1\Menu\CategoryItem;
use App\Models\Api\v1\Menu\FoodItem;
use App\Models\Api\v1\Reservation;
use App\Models\Api\v1\ReservationCapacity;
use App\Models\Api\v1\Time\DatesOffs;
use App\Models\Api\v1\Time\DaysOff;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function menu(){
        $SectionItems = SectionItem::where('active', true)->get();
        $CategoryItems = CategoryItem::where('active', true)->get();
        $FoodItems = FoodItem::where('active', true)->get();

        return response()->json([
            'sectionItems' => $SectionItems,
            'categoryItems' => $CategoryItems,
            'foodItems' => $FoodItems 
        ]);
    }

    public function reservations(){
        $regulations = ReservationCapacity::all();
        $max = $regulations->daily_capacity;
        $booked = Reservation::groupBy('reservation_date')
        ->having(Reservation::raw('count(reservation_date)'), '>=', $max)
        ->pluck('reservation_date');
        $daysOff = DaysOff::all();
        $datesOff = DatesOffs::all();

        return response()->json([
            "regulations" => $regulations,
            "booked" => $booked,
            "daysOff" => $daysOff,
            "datesOff" => $datesOff
        ], 200);
    }

}
