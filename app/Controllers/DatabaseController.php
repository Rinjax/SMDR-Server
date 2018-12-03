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
            $this->manager->insert('cdr_ucm', $dataArray);
            $this->logger->debug('record inserted');

        }catch(\Exception $e){
            $this->logger->error('Failed to write to database >>> ' . $e->getMessage());
        }

    }
}