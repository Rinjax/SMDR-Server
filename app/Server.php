<?php

namespace App;

use App\Controllers\DatabaseController;
use App\Controllers\SMDRLogController;
use App\Managers\DatabaseManager;
use App\Managers\ServerPort;


class Server
{
    protected $socket;

    protected $ServerPort;

    protected $SMDRController;

    protected $DatabaseController;




    public function __construct()
    {
       $this->ServerPort = new ServerPort();

       $this->socket = $this->ServerPort->buildPort();

       $this->SMDRController = new SMDRLogController();

       $this->DatabaseController = new DatabaseController();

       $this->test = new DatabaseManager();

    }

    public function run()
    {

        do{
            $packet = stream_socket_recvfrom($this->socket, 1500, 0);

            $this->SMDRController->logToFile($packet);

            $smdrOject= $this->SMDRController->interpretSMDR($packet);

            $this->DatabaseController->writeSMDRToDatabase($smdrOject->map);

            // relay to mycalls
        }

        while ($packet !== false);

    }

}