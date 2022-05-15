<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UploadImage;

class UploadImageController extends Controller
{
    function show(){
		return view("upload_form");
	}

	function upload(Request $request){
		$request->validate([
			'image' => 'required|file|image|mimes:png,jpeg'
		]);
		$upload_image = $request->file('image');

		if($upload_image) {                                            //アップロードされた画像を保存する
			$path = $upload_image->store('uploads', 'public');

			if($path){  			                                   //画像の保存に成功したらDBに記録する
                $userName = Auth::user()->name;

                if(UploadImage::where('user', $userName)->exists()) {  //既にユーザのアイコンが存在する場合
                    $file = UploadImage::where('user', $userName)->first();
                    $filePath = $file->file_path;
                    Storage::delete("public/" . $filePath);            //以前の画像ファイルを削除する

                    UploadImage::where('user', $userName)
                        ->update([
                            'file_name' => $upload_image->getClientOriginalName(),
                            "file_path" => $path
                        ]);
                }
                else { //アイコンが登録されていない場合
                    UploadImage::create([
                        "file_name" => $upload_image->getClientOriginalName(),
                        "file_path" => $path,
                        "user" => $userName
                    ]);
                }
			}
		}
		return redirect("/chat");
	}
}
