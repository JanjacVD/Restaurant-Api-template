<?php

namespace App\Http\Controllers\Api\v1\Time;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Api\v1\Time\DaysOff;
use Illuminate\Support\Facades\Validator;

class DaysOffController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin') {
            $days = DaysOff::all();
            return response()->json(['days' => $days], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role == 'Admin') {

            $DayOff = DaysOff::findOrFail($id);
            $DayOff->delete();
            return response()->json(['Status' => 'Day off sucessfully deleted'], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin') {
            $validator = Validator::make(
                $request->all(),
                [
                    'day' => ['required', 'integer'],
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $DayOff = new DaysOff;
                $DayOff->day = $request->day;
                $DayOff->save();
                return response()->json(['Status' => 'Day off sucessfully added'], 201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
