<?php

namespace App\Http\Controllers\Api\v1\Menu;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Menu\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FoodItemController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin') {
            $FoodItems = FoodItem::all();
            return response()->json(['FoodItems' => $FoodItems], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->role == 'Admin') {
            $FoodItems = FoodItem::where('id', $id)->get();
            return response()->json(['FoodItems' => $FoodItems], 200);
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
                    'title_hr' => ['required', 'string', 'max:255'],
                    'title_en' => ['required', 'string', 'max:255'],
                    'desc_hr' => ['required', 'string', 'max:255'],
                    'desc_en' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'integer', 'max:255'],
                    'category_id' => ['required', 'integer', 'max:255'],
                    'image_name' => ['required', 'string', 'max:255'],
                    'active' => ['required', 'boolean']
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $FoodItem = FoodItem::findOrfail($id);
                $FoodItem->title_hr = $request->title_hr;
                $FoodItem->title_en = $request->title_en;
                $FoodItem->desc_hr = $request->desc_hr;
                $FoodItem->desc_en = $request->desc_en;
                $FoodItem->price = $request->price;
                $FoodItem->category_id = $request->category_id;
                $FoodItem->image_name = $request->image_name;
                $FoodItem->active = $request->active;
                $FoodItem->save();
                return response()->json(['Status' => 'Succesfully updated'], 201);
            }
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
                    'title_hr' => ['required', 'string', 'max:255'],
                    'title_en' => ['required', 'string', 'max:255'],
                    'desc_hr' => ['required', 'string', 'max:255'],
                    'desc_en' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'integer', 'max:255'],
                    'category_id' => ['required', 'integer', 'max:255'],
                    'image_name' => ['required', 'string', 'max:255'],
                    'active' => ['required', 'boolean']
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $FoodItem = new FoodItem;
                $FoodItem->title_hr = $request->title_hr;
                $FoodItem->title_en = $request->title_en;
                $FoodItem->desc_hr = $request->desc_hr;
                $FoodItem->desc_en = $request->desc_en;
                $FoodItem->price = $request->price;
                $FoodItem->category_id = $request->category_id;
                $FoodItem->image_name = $request->image_name;
                $FoodItem->active = $request->active;
                $FoodItem->save();
                return response()->json(['Status' => 'Item sucessfully created'], 201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role == 'Admin') {
            $FoodItem = FoodItem::findOrFail($id);
            $FoodItem->delete();
            return response()->json(['Status' => 'Item sucessfully deleted'], 201);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
