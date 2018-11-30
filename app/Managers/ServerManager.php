<?php

namespace App\Managers;


class ServerManager
{
    protected $socket;

    protected $ServerPort;

    protected $FileLogger;

    protected $DatabaseManager;

    protected $SMDRInterpreter;


    public function __construct()
    {
       $this->ServerPort = new ServerPort();

       $this->FileLogger = new FileLogger();

       $this->socket = $this->ServerPort->buildPort();

       $this->DatabaseManager = new DatabaseManager();

       $this->SMDRInterpreter = new SMDRInterpreter();

    }

    public function run()
    {
        do{
            $packet = stream_socket_recvfrom($this->socket, 1500, 0);

            $this->writeToLogFile($packet);

            // write to sql

            // relay to mycalls
        }

        while ($packet !== false);

    }

}