<?php

namespace App\Managers;

use App\Schemas\NEC3C;

class SMDRInterpreter
{
    protected $schema;

    protected $logger;

    public function __construct()
    {
        $this->schema = getenv('SMDR_SCHEMA');

        $this->logger = new LogManager('smdr-interpreter');
    }

    public function smdrToArray($smdr)
    {
        $array = explode(',', $smdr);

        $this->logger->debug('Array length is ' . count($array));

        return $array;
    }

    public function arrayToSchema($array)
    {
        switch($this->schema){
            case 'nec3c':
                return new NEC3C($array);
                break;
            default:
                $this->logger->error('Failed to match SMDR schema');
                die('Failed to match SMDR schema');
        }
    }
}