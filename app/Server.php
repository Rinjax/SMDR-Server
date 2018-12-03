<?php

namespace App;

use App\Controllers\DatabaseController;
use App\Controllers\SMDRLogController;
use App\Managers\ServerPort;


class Server
{
    protected $RxSocket;

    protected $TxSocket;

    protected $ServerPort;

    protected $SMDRController;

    protected $DatabaseController;




    public function __construct()
    {
       $this->ServerPort = new ServerPort();

       $this->RxSocket = $this->ServerPort->buildReceivingPort();

       $this->TxSocket = $this->ServerPort->buildSendingPort();

       $this->SMDRController = new SMDRLogController();

       $this->DatabaseController = new DatabaseController();

    }

    public function run()
    {

        do{
            $packet = stream_socket_recvfrom($this->socket, 1500, 0);

            $this->SMDRController->logToFile($packet);

            $smdrOject= $this->SMDRController->interpretSMDR($packet);

            $this->DatabaseController->writeSMDRToDatabase($smdrOject->map);

            fwrite($this->TxSocket, $packet);
        }

        while ($packet !== false);

    }

}