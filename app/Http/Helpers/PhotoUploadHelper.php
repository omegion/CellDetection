<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Image;

class PhotoUploadHelper
{

    public static function upload($image_raw)
    {
        $image_data = $image_raw;
        $file_extension = explode('/', explode(':', substr($image_data, 0, strpos($image_data, ';')))[1])[1];
        $random_name = Carbon::now()->timestamp . '_' . str_random(10);
        $file_name =  $random_name . '.jpg';
        $image = Image::make($image_raw);
        $image->encode('jpg', 100);
        $image->save(storage_path('app/public/images/').$file_name);
        return $file_name;
        
    }

}