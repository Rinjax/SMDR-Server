<?php

namespace App\Managers;

use App\Schemas\NEC3C;

class SMDRInterpreter
{
    protected $schema;

    public function __construct()
    {
        $this->schema = getenv('SMDR_SCHEMA');
    }

    public function smdrToArray($smdr)
    {
         return explode(',', $smdr);
    }

    public function arrayToSchema($array)
    {
        switch($this->schema){
            case 'nec3c':
                return new NEC3C($array);
                break;
            default:
                die('Failed to build SMDR schema');
        }
    }
}