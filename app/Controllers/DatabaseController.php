<?php

namespace App\Controllers;

use App\Managers\DatabaseManager;
use App\Managers\LogManager;

class DatabaseController
{
    protected $manager;

    protected $logger;

    public function __construct()
    {
        $this->manager = new DatabaseManager();

        $this->logger = new LogManager('database-controller');
    }

    /**
     * Connect to the database and insert the data to the table
     * @param $dataArray
     */
    public function writeSMDRToDatabase($dataArray)
    {
        try{
            $this->logger->debug('Connecting to database.');
            $this->manager->connectToDatabase();
            $this->logger->debug('Connection made.');

        }catch(\Exception $e){
            $this->logger->error('Failed to connect to database >>> ' . $e->getMessage());
        }

        try{
            $this->logger->debug('Beginning SQL insert.');
            $insert = $this->manager->insert('cdr_ucm', $dataArray);
            if($insert){
                $this->logger->debug('record inserted');
            }else{
                $this->logger->error('record not inserted');
            }


        }catch(\Exception $e){
            $this->logger->error('Failed to write to database >>> ' . $e->getMessage());
        }

    }
}