<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PhotoUploadHelper;
use Illuminate\Support\Facades\Storage;

class CellDetectionController extends Controller
{
    public $types = array(
        1 => 'red',
        2 => 'platelet',
        3 => 'basophils',
        4 => 'eosinophils',
        5 => 'lymphocytes',
        6 => 'monocytes',
        7 => 'neutrophils',
    );

    public function index() 
    {
        $file_name = '1542573406_RouQx9R3Cy.jpg';
        $file_name_arr = explode(".", $file_name);

        $directory = 'public/images/'.$file_name_arr[0];
        $files = Storage::disk('local')->files($directory);

        $crops = collect();
        foreach ($files as $key => $file_path) {
            $file_path_arr = explode('/', $file_path);
            $crop_name = $file_path_arr[count($file_path_arr)-1];
            $crop_name_arr = explode('.', $crop_name);
            $crop_details_arr = explode('_', $crop_name_arr[0]);
            $crop_score = $crop_details_arr[0];
            $crop_type = $crop_details_arr[1];
            $crop_type = $this->types[$crop_type];
            $crops[] = array(
                'score' => $crop_score, 
                'type' => $crop_type, 
                'source' => $file_name_arr[0].'/'.$crop_name
            );
        }
        return view('index')
            ->with('crops', $crops);
    }

    public function upload(Request $request) 
    {
        $file_name = PhotoUploadHelper::upload($request->image);
        $this->run($file_name, $request->type);
        $file_name_arr = explode(".", $file_name);

        $directory = 'public/images/'.$file_name_arr[0];
        $files = Storage::disk('local')->files($directory);

        $crops = array();
        foreach ($files as $key => $file_path) {
            $file_path_arr = explode('/', $file_path);
            $crop_name = $file_path_arr[count($file_path_arr)-1];
            $crop_name_arr = explode('.', $crop_name);
            $crop_details_arr = explode('_', $crop_name_arr[0]);
            $crop_score = $crop_details_arr[0];
            $crop_type = $crop_details_arr[1];
            $crop_type = $this->types[$crop_type];
            $crops[] = array(
                'score' => $crop_score, 
                'type' => $crop_type, 
                'source' => $file_name_arr[0].'/'.$crop_name
            );
        }

        return response()->json(['file' => $file_name, 'crops' => $crops]);

    }

    public function run($file_name, $type) 
    {
        // if (config('app.env' == 'local')) {
            // $process = new Process("python /home/vagrant/code/CellDetection/python/models/research/object_detection/test.py ".$file_name." ".$type);
        // } else {
            $process = new Process("python /var/www/bloodcell.xyz/python/models/research/object_detection/test.py ".$file_name." ".$type);
        // }
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return (json_decode($process->getOutput(), true));
    }
}
