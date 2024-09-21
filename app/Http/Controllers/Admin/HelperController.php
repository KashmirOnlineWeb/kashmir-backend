<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelperController extends Controller
{
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
                        ]);

            $file     = $request->file('image');
            $fileName = time().uniqid().'.'.$file->extension();
            $response = Storage::put('public/images/'.$fileName, file_get_contents($file->getRealPath()));

            $data = ['status' => $response, 'path' => ''];
            if($response){
                $path = Storage::get($fileName);
                $data['path'] = 'images/'.$fileName;
                return response()->json($data, 200);
            }
            return response()->json($data, 200);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }
}
