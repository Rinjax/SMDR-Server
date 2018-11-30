<?php

namespace App\Managers;

class SMDRInterpreter
{
    protected $schema;

    public function __construct()
    {
        $this->schema = getenv('SMDR_SCHEMA');
    }

    public function smdrToArray($smdr)
    {
         $array = explode(',', $smdr);
    }
}