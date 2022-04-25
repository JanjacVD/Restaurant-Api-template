<?php

namespace App\Http\Controllers\Api\v1\Time;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\v1\Time\DatesOffs;
use Illuminate\Support\Facades\Auth;

class DatesOffController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Manager') {
            $DatesOff = DatesOffs::all();
            return response()->json(['dates' => $DatesOff], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Manager') {

            $DatesOff = DatesOffs::findOrFail($id);
            $DatesOff->delete();
            return response()->json(['Status' => 'Date off sucessfully deleted'], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Manager') {
            $validator = Validator::make(
                $request->all(),
                [
                    'date_off' => ['required', 'date_format:Y-m-d'],
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $DateOff = new DatesOffs;
                $DateOff->date_off = $request->date_off;
                $DateOff->save();
                return response()->json(['Status' => 'Date off sucessfully added'], 201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
