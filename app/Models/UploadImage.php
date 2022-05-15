<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadImage extends Model
{
    use HasFactory;

    protected $table = "upload_image";
	protected $fillable = [
        "file_name",
        "file_path",
        "user",
    ];
}
