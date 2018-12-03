<?php

namespace App\Controllers;

use App\Managers\SMDRFileLogger;
use App\Managers\SMDRInterpreter;

class SMDRLogController
{
    protected $manager;

    protected $interpreter;

    public function __construct()
    {
        $this->manager = new SMDRFileLogger();

        $this->interpreter = new SMDRInterpreter();
    }

    /**
     * Record the received SMDR data to a log file.
     * @param $packet
     */
    public function logToFile($packet)
    {
        $this->manager->writeToLogFile($packet);
    }

    /**
     * replay the SMDR data back out to the MyCalls server
     * @param $packet
     */
    public function relaySMDR($packet)
    {

    }

    /**
     * Translate SMDR into correct schema object.
     * @param $packet
     * @return \App\Schemas\NEC3C
     */
    public function interpretSMDR($packet)
    {
        return $this->interpreter->arrayToSchema($this->interpreter->smdrToArray($packet));
    }
}