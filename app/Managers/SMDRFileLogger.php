<?php

namespace App\Managers;

use Carbon\Carbon;

class SMDRFileLogger
{
    //log file that the SMDR will be recorded to.
    protected $logFile;

    //logger class for recording log entries.
    protected $logger;

    public function __construct()
    {
        $this->logFile = APP_ROOT . '/Logs/SMDR/' . Carbon::now()->format('Y-m-d') . '-SMDR.log';

        $this->logger = new LogManager('smdr-file-logger');
    }

    /**
     * Write the capture SMDR packet to the log file
     * @param $packet
     */
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