<?php

namespace App\Managers;

class ServerPort
{
    protected $receivingSocket;

    protected $receivingIp;

    protected $receivingPort;

    protected $sendingSocket;

    protected $sendingIp;

    protected $sendingPort;

    public function __construct()
    {
        $this->receivingIp = getenv('SMDR_IP');
        $this->receivingPort = getenv('SMDR_PORT');

        $this->sendingIp = getenv('RELAY_IP');
        $this->sendingPort = getenv('RELAY_PORT');
    }

    public function buildReceivingPort()
    {


        $this->receivingSocket = stream_socket_server("udp://" . $this->receivingIp .":" . $this->receivingPort, $errno, $errstr, STREAM_SERVER_BIND);
        if (!$this->receivingSocket) {
            die("$errstr ($errno)");
        }

        return $this->receivingSocket;
    }

    public function buildSendingPort()
    {
        $this->sendingSocket = stream_socket_client("udp://" . $this->sendingIp .":" . $this->sendingPort, $errno, $errstr, 2);
        if (!$this->sendingSocket) {
            die("$errstr ($errno)");
        }

        return $this->sendingSocket;
    }
}