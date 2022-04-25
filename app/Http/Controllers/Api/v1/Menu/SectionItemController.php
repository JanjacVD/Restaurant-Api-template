<?php

namespace App\Http\Controllers\Api\v1\Menu;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Menu\CategoryItem;
use Illuminate\Http\Request;
use App\Models\Api\v1\Menu\SectionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SectionItemController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin') {
            $SectionItems = SectionItem::all();
            return response()->json(['SectionItems' => $SectionItems], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function show($id)
    {
        if (Auth::user()->role == 'Admin') {
            $SectionItem = SectionItem::findOrFail($id);
            $CategoryItems = CategoryItem::where('category_id', $id)->get();
            return response()->json(['SectionItem' => $SectionItem, 'CategoryItems' => $CategoryItems], 200);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->role == 'Admin') {
            $SectionItems = SectionItem::where('id', $id)->get();
            return response()->json(['SectionItems' => $SectionItems], 200);
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
                    'active' => ['required', 'boolean']
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $SectionItem = SectionItem::findOrfail($id);
                $SectionItem->title_hr = $request->title_hr;
                $SectionItem->title_en = $request->title_en;
                $SectionItem->active = $request->active;
                $SectionItem->save();
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
                    'active' => ['required', 'boolean']
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            } else {
                $SectionItem = new SectionItem;
                $SectionItem->title_hr = $request->title_hr;
                $SectionItem->title_en = $request->title_en;
                $SectionItem->active = $request->active;
                $SectionItem->save();
                return response()->json(['Status' => 'Section sucessfully created'], 201);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role == 'Admin') {
            $SectionItem = SectionItem::findOrFail($id);
            CategoryItem::where('section_id', $id)->delete();
            $SectionItem->delete();
            return response()->json(['Status' => 'Section sucessfully deleted'], 201);
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }
}
