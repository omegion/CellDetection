<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PhotoUploadHelper;

class CellDetectionController extends Controller
{
    public function index() 
    {
        return view('index');
    }

    public function upload(Request $request) 
    {
        $file_name = PhotoUploadHelper::upload($request->image);

        $this->run($file_name);

        return $file_name;
    }

    public function run($file_name) 
    {
        $process = new Process("python /home/vagrant/code/CellDetection/python/models/research/object_detection/test.py ".$file_name);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return (json_decode($process->getOutput(), true));
    }
}
