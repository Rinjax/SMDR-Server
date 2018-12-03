<?php

namespace App\Controllers;

use App\Managers\LogManager;
use App\Managers\SMDRFileLogger;
use App\Managers\SMDRInterpreter;

class SMDRLogController
{
    protected $manager;

    protected $interpreter;

    protected $logger;

    public function __construct()
    {
        $this->manager = new SMDRFileLogger();

        $this->interpreter = new SMDRInterpreter();

        $this->logger = new LogManager('smdr-controller');
    }

    /**
     * Record the received SMDR data to a log file.
     * @param $packet
     */
    public function logToFile($packet)
    {
        $this->logger->debug('Writing to SMDR log file');
        $this->manager->writeToLogFile($packet);
    }

    /**
     * Translate SMDR into correct schema object.
     * @param $packet
     * @return \App\Schemas\NEC3C
     */
    public function interpretSMDR($packet)
    {
        $this->logger->debug('Matching SMDR to Schema');
        return $this->interpreter->arrayToSchema($this->interpreter->smdrToArray($packet));
    }
}