<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\ReservationCapacity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationCapacityController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin') {
            $settings = ReservationCapacity::first();
            return response()->json(['Settings' => $settings], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin') {
            if (ReservationCapacity::count() > 0) {
                return response()->json(['Status' => 'Settings already exist'], 418);
            }
            $validator = Validator::make(
                $request->all(),
                [
                    'daily_capacity' => ['required', 'integer'],
                    'table_capacity'  => ['required', 'integer'],
                    'reservation_status'  => ['required', 'integer'],
                    'min_time' => ['required', 'date_format:H:i'],
                    'max_time' => ['required', 'date_format:H:i'],
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $settings = new ReservationCapacity;
                $settings->daily_capacity = $request->daily_capacity;
                $settings->table_capacity = $request->table_capacity;
                $settings->reservation_status = $request->reservation_status;
                $settings->min_time = $request->min_time;
                $settings->max_time = $request->max_time;
                $settings->save();
                return response()->json(['Status' => 'Sucessfully created'],201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->role == 'Admin') {
            $settings=ReservationCapacity::FindOrFail($id);
            return response()->json(['Settings' => $settings], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role == 'Admin') {
            $settings=ReservationCapacity::FindOrFail($id);

            $validator = Validator::make(
                $request->all(),
                [
                    'daily_capacity' => ['required', 'integer'],
                    'table_capacity'  => ['required', 'integer'],
                    'reservation_status'  => ['required', 'integer'],
                    'min_time' => ['required', 'date_format:H:i'],
                    'max_time' => ['required', 'date_format:H:i'],
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $settings->daily_capacity = $request->daily_capacity;
                $settings->table_capacity = $request->table_capacity;
                $settings->reservation_status = $request->reservation_status;
                $settings->min_time = $request->min_time;
                $settings->max_time = $request->max_time;
                $settings->save();
                return response()->json(['Status' => 'Sucessfully updated'],201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
