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
        if (config('app.env' == 'local')) {
            $process = new Process("python /home/vagrant/code/CellDetection/python/models/research/object_detection/test.py ".$file_name);
        } else {
            $process = new Process("python /var/www/bloodcell.xyz/python/models/research/object_detection/test.py ".$file_name);
        }
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return (json_decode($process->getOutput(), true));
    }
}
