<?php

namespace App\Managers;

use Carbon\Carbon;

class SMDRFileLogger
{
    protected $logFile;

    protected $logger;

    public function __construct()
    {
        $this->logFile = APP_ROOT . '/Logs/SMDR/' . Carbon::now()->format('Y-m-d') . '-SMDR.log';

        $this->logger = new LogManager('smdr-file-logger');
    }
    public function writeToLogFile($packet)
    {
        try{
            file_put_contents($this->logFile, $packet . "\r\n", FILE_APPEND);
        }catch(\Exception $e){
            $this->logger->error('Failed to write to SMDR log file >>> ' . $e->getMessage());
            die($e->getMessage());
        }
    }
}