<?php

namespace App\Controllers;

use App\Managers\DatabaseManager;

class DatabaseController
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new DatabaseManager();
    }

    public function writeSMDRToDatabase($dataArray)
    {
        $this->manager->connectToDatabase();

        $this->manager->insert('cdr_ucm', $dataArray);

        //close

    }
}