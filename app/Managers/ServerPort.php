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

    protected $logger;

    public function __construct()
    {
        $this->receivingIp = getenv('SMDR_IP');
        $this->receivingPort = getenv('SMDR_PORT');

        $this->sendingIp = getenv('RELAY_IP');
        $this->sendingPort = getenv('RELAY_PORT');

        $this->logger = new LogManager('server-port');
    }

    public function buildReceivingPort()
    {
        $this->logger->info('Building Rx Socket with IP: ' . $this->receivingIp . ' on port: ' . $this->receivingPort);

        $this->receivingSocket = stream_socket_server("udp://" . $this->receivingIp .":" . $this->receivingPort, $errno, $errstr, STREAM_SERVER_BIND);
        if (!$this->receivingSocket) {
            $this->logger->error('Failed Building Rx Socket >>> ' . "$errstr ($errno)");
            die("$errstr ($errno)");
        }

        $this->logger->info('Rx Socket started.');

        return $this->receivingSocket;
    }

    public function buildSendingPort()
    {

        $this->logger->info('Building Tx Socket with IP: ' . $this->sendingIp . ' on port: ' . $this->sendingPort);

        $this->sendingSocket = stream_socket_client("udp://" . $this->sendingIp .":" . $this->sendingPort, $errno, $errstr, 2);
        if (!$this->sendingSocket) {
            $this->logger->error('Failed Building Tx Socket >>> ' . "$errstr ($errno)");
            die("$errstr ($errno)");
        }

        $this->logger->info('Tx Socket started.');

        return $this->sendingSocket;
    }

    public function getReceivingIp()
    {
        return $this->receivingIp;
    }

    public function getReceivingPort()
    {
        return $this->receivingPort;
    }

    public function getSendingIp()
    {
        return $this->sendingIp;
    }

    public function getSendingPort()
    {
        return $this->sendingPort;
    }
}