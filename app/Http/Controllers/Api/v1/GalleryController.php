<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\v1\Gallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class GalleryController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'Admin') {
            $gallery = Gallery::all();
            return response(['gallery' => $gallery], 200);
        } else {
            return response(['Status' => 'Unauthorized', 401]);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role == 'Admin') {

            $validatedData = $request->validate([
                'title' => 'required|max:100',
                'title_en' => 'required|max:100',
                'active' => 'required'
            ]);
            $gallery = Gallery::findOrFail($id);
            $gallery->fill($validatedData);
            $gallery->save();

            return response(['Status' => 'Successfully updated'], 201);
        } else {
            return response(['Status' => 'Unauthorized', 401]);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin') {

            $validatedData = $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg|max:15000',
                'title' => 'required|max:100',
                'title_en' => 'required|max:100',
                'active' => 'required'
            ]);
            $thumbPath = STORAGE::path('images/thumbs');
            $imagePath = STORAGE::path('images/gallery');
            $file = $request->file('image');
            $imageName = $request->name;
            $thumbName = $request->name;
            $img = Image::make($file)
                ->encode('jpg', 50);;
            $thumb = Image::make($file)
                ->encode('jpg', 50);;
            $thumb->save($thumbPath . '/' . $thumbName . '.jpg', 15);
            $img->save($imagePath . '/' . $imageName . '.jpg', 60);

            $gallery = Gallery::make($validatedData);
            $gallery->save();
            return response(['Status' => 'Image successfully added'], 201);
        } else {
            return response(['Status' => 'Unauthorized', 401]);
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->role == 'Admin') {

            $image = Gallery::findOrFail($id);
            Storage::delete('/images/thumbs/' . $image->name . '.jpg');
            Storage::delete('/images/gallery/' . $image->name . '.jpg');

            $image->delete();

            return response(['Status' => 'Succesfully deleted'], 200);
        } else {
            return response(['Status' => 'Unauthorized', 401]);
        }
    }
}
