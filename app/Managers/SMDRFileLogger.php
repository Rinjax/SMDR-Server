<?php

namespace App\Managers;

use Carbon\Carbon;

class SMDRFileLogger
{
    protected $logFile;

    public function __construct()
    {
        $this->logFile = APP_ROOT . '/Logs/' . Carbon::now()->format('Y-m-d') . '-SMDR.log';
    }
    public function writeToLogFile($packet)
    {
        try{
            file_put_contents($this->logFile, $packet . "\r\n", FILE_APPEND);
        }catch(\Exception $e){
            die($e->getMessage());
        }
    }
}