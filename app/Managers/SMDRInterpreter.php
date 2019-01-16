<?php

namespace App\Managers;

use App\Schemas\NEC3C;

class SMDRInterpreter
{
    //Schema that the class is currently locked into - set from the ENV file.
    protected $schema;

    //logger class for recording log entries.
    protected $logger;

    public function __construct()
    {
        $this->schema = getenv('SMDR_SCHEMA');

        $this->logger = new LogManager('smdr-interpreter');
    }

    /**
     * Convert the SMDR data to an array
     * @param $smdr
     * @return array
     */
    public function smdrToArray($smdr)
    {
        $array = explode(',', $this->cleanEscapedCommas($smdr));

        $this->logger->debug('Array length is ' . count($array), $array);

        return $array;
    }

    /**
     * get the schema class that will match the smdr array to the correct array keys that will be need to storing to
     * the database
     * @param $array
     * @return NEC3C
     */
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

    /**
     * Remove any escaped commas \, as this screws up the breaking up into an array by comma. Replace with {!} mark so
     * we can search and action later.
     * @param $smdr
     * @return mixed
     */
    protected function cleanEscapedCommas($smdr)
    {
        return str_replace('\,', '{!}', $smdr);
    }
}