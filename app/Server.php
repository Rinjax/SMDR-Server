<?php

namespace App;

use App\Controllers\DatabaseController;
use App\Controllers\SMDRController;
use App\Managers\LogManager;
use App\Managers\ServerPort;
use App\Managers\SMDRInterpreter;


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

       $this->SMDRController = new SMDRController();

       $this->DatabaseController = new DatabaseController();

       $this->logger = new LogManager('server');

    }

    /**
     * Main function to start running the SMDR server and start accepting packets
     */
    public function run()
    {
        $this->logger->info('Server has started and is listening on ' . $this->ServerPort->getReceivingIp() . ':' . $this->ServerPort->getReceivingPort());

        do{
            $this->logger->debug('listening for packets.');
            $packet = stream_socket_recvfrom($this->RxSocket, 1500, 0);
            $this->logger->debug('Received: ' . $packet);

            $this->SMDRController->logToFile($packet);

            $this->logger->debug('Interpreting data using schema: ' . getenv('SMDR_SCHEMA'));
            $smdrOject= $this->SMDRController->interpretSMDR($packet);

            $this->DatabaseController->writeSMDRToDatabase($smdrOject->map);

            $this->logger->debug('Sending to Mycalls.');

            fwrite($this->TxSocket, $packet);

        }

        while ($packet !== false);

    }

    /**
     * Test function to develop the SMDR strings
     */
    public function test()
    {
        $inter = new SMDRInterpreter();

        $smdr = "2,17184963,Global 4 Com,pstn,+441403272910,Hooper\, Dwayne,ext,1217,,,,0,5,1547569776,63,Trunk:1,,,,,,17187988,,global4\\Dwayne.Hooper,,9,,,,,,no,no,no,no,no,no,0,01403216134";

        $obj = $inter->arrayToSchema($inter->smdrToArray($smdr));
        var_dump($obj);
    }
    
}