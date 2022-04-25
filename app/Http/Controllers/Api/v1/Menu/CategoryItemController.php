<?php

namespace App\Http\Controllers\Api\v1\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\v1\Menu\CategoryItem;
use App\Models\Api\v1\Menu\FoodItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CategoryItemController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin') {
            $CategoryItems = CategoryItem::all();
            return response()->json(['CategoryItems' => $CategoryItems], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function show($id)
    {
        if (Auth::user()->role == 'Admin') {
            $CategoryItem = CategoryItem::findOrFail($id);
            $FoodItems = FoodItem::where('category_id', $id)->get();
            return response()->json(['CategoryItems' => $CategoryItem, 'FoodItems' => $FoodItems]);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->role == 'Admin') {
            $CategoryItems = CategoryItem::where('id', $id)->get();
            return response()->json(['CategoryItems' => $CategoryItems], 200);
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
                    'section_id' => ['required', 'integer'],
                    'active' => ['required', 'boolean']
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $CategoryItem = CategoryItem::findOrfail($id);
                $CategoryItem->title_hr = $request->title_hr;
                $CategoryItem->title_en = $request->title_en;
                $CategoryItem->section_id = $request->section_id;
                $CategoryItem->active = $request->active;
                $CategoryItem->save();
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
                    'section_id' => ['required', 'integer'],
                    'active' => ['required', 'boolean']
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $CategoryItem = new CategoryItem;
                $CategoryItem->title_hr = $request->title_hr;
                $CategoryItem->title_en = $request->title_en;
                $CategoryItem->section_id = $request->section_id;
                $CategoryItem->active = $request->active;
                $CategoryItem->save();
                return response()->json(['Status' => 'Category sucessfully created'], 201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role == 'Admin') {
            $CategoryItem = CategoryItem::findOrFail($id);
            FoodItem::where('category_id', $id)->delete();
            $CategoryItem->delete();
            return response()->json(['Status' => 'Category sucessfully deleted'], 201);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
