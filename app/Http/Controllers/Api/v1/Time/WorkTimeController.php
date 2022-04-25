<?php

namespace App\Http\Controllers\Api\v1\Time;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Time\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkTimeController extends Controller
{
    public function index()
    {
            $WorkTime = WorkTime::first();
            return response()->json(['WorkTime' => $WorkTime], 200);
    }
    public function edit($id)
    {
        if (Auth::user()->role == 'Admin') {
            $WorkTime = WorkTime::where('id', $id)->get();
            return response()->json(['SectionItems' => $WorkTime], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role == 'Admin') {
            $validator = Validator::make(
                $request->all(),
                [
                    'day_from' => ['reqired', 'integer'],
                    'day_to' => ['required', 'integer'],
                    'time_from' => ['required', 'date_format:H:i'],
                    'time_to' => ['required', 'date_format:H:i'],
                    'is_open' => ['required', 'boolean'],
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $WorkTime = WorkTime::findOrfail($id);
                $WorkTime->day_from = $request->day_from;
                $WorkTime->day_to = $request->day_to;
                $WorkTime->time_from = $request->time_from;
                $WorkTime->time_to = $request->time_to;
                $WorkTime->is_open = $request->active;
                $WorkTime->save();
                return response()->json(['Status' => 'Succesfully updated'], 201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin') {
            if (WorkTime::count() > 0) {
                return response()->json(['Status' => 'Work time already exists'], 418);
            } else {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'day_from' => ['required', 'integer'],
                        'day_to' => ['required', 'integer'],
                        'time_from' => ['required', 'date_format:H:i'],
                        'time_to' => ['required', 'date_format:H:i'],
                        'is_open' => ['required', 'boolean'],
                    ]
                );
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
                } else {
                    $WorkTime = new WorkTime;
                    $WorkTime->day_from = $request->day_from;
                    $WorkTime->day_to = $request->day_to;
                    $WorkTime->time_from = $request->time_from;
                    $WorkTime->time_to = $request->time_to;
                    $WorkTime->is_open = $request->active;
                    $WorkTime->save();
                    return response()->json(['Status' => 'Work time sucessfully created'], 201);
                }
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
