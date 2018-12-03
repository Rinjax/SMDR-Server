<?php

namespace App;

use App\Controllers\DatabaseController;
use App\Controllers\SMDRLogController;
use App\Managers\LogManager;
use App\Managers\ServerPort;


class Server
{
    //Server's Receiving Socket
    protected $RxSocket;

    //Server's Transmitting Socket
    protected $TxSocket;

    //Server's Port Manager.
    protected $ServerPort;

    //SMDR Controller to handle SMDR processing.
    protected $SMDRController;

    //Database Controller to handle DB processing.
    protected $DatabaseController;

    //logger class for recording log entries.
    protected $logger;


    public function __construct()
    {
       $this->ServerPort = new ServerPort();

       $this->RxSocket = $this->ServerPort->buildReceivingPort();

       $this->TxSocket = $this->ServerPort->buildSendingPort();

       $this->SMDRController = new SMDRLogController();

       $this->DatabaseController = new DatabaseController();

       $this->logger = new LogManager('server');

    }

    /**
     *
     */
    public function run()
    {
        $this->logger->info('Server has started and is listening on ' . $this->ServerPort->getReceivingIp() . ':' . $this->ServerPort->getReceivingPort());

        do{
            $this->logger->debug('Receiving packets.');
            $packet = stream_socket_recvfrom($this->RxSocket, 1500, 0);
            $this->logger->debug('Received: ' . $packet);

            $this->SMDRController->logToFile($packet);

            $this->logger->debug('Interpreting data using schema: ' . getenv('SMDR_SCHEMA'));
            $smdrOject= $this->SMDRController->interpretSMDR($packet);

            $this->DatabaseController->writeSMDRToDatabase($smdrOject->map);

            fwrite($this->TxSocket, $packet);
        }

        while ($packet !== false);

    }

}