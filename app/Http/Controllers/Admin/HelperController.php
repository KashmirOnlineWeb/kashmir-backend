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
                'file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,webm,mov,avi,mkv|max:51200' // 50MB max
            ]);

            $file = $request->file('file');
            //dd($file);
            $extension = $file->extension();
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
            $isVideo = in_array($extension, ['mp4', 'webm', 'mov', 'avi', 'mkv']);
            //$folder = $isImage ? 'images/' : ($isVideo ? 'videos/' : 'files/');
            $folder = 'images/';
            $fileName = time().uniqid().'.'.$extension;
            $response = $file->storeAs($folder, $fileName);

            $data = ['status' => (bool)$response, 'path' => ''];
            if($response){
                $data['path'] = $folder.$fileName;
                return response()->json($data, 200);
            }
            return response()->json($data, 200);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }
}
