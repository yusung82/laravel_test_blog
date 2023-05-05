<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{

    /*
     * file upload
     *
     *
      aws id: s3-laravel-uploader
     *
     */
    public function upload(Request $request){
        $file = $request->file('file');
//        logger($file);
//        logger($file->isValid());
//        logger($file->extension());
//        logger($file->getClientOriginalName());
//        logger($file->getClientMimeType());

        $dir =date('y/m'); //  /public/storage/uploads/  23/05  저장폴더 관리

        $path = $file->store('uploads/'.$dir, 's3');
        //$path = $file->storePublicly('uploads/'.$dir, 's3');

        return response()->json(['path'=>$path]);
    }

}
