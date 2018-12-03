<?php

namespace App\Managers;

class ServerPort
{
    //the socket the server will receive data on
    protected $receivingSocket;

    //IP address that the receiving socket will be bound to.
    protected $receivingIp;

    //port that the receiving socket will be bound to.
    protected $receivingPort;

    //the socket the server will send data on.
    protected $sendingSocket;

    //IP address that the transmitting socket will send to (destination).
    protected $sendingIp;

    //port that the server will send data to (destination).
    protected $sendingPort;

    //logger class for recording log entries.
    protected $logger;

    public function __construct()
    {
        $this->receivingIp = getenv('SMDR_IP');
        $this->receivingPort = getenv('SMDR_PORT');

        $this->sendingIp = getenv('RELAY_IP');
        $this->sendingPort = getenv('RELAY_PORT');

        $this->logger = new LogManager('server-port');
    }

    /**
     * Build the server's receiving port
     * @return resource
     */
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

    /**
     * Build the server's transmitting port.
     * @return bool|resource
     */
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

    /**
     * return the IP address that the server's Rx socket is bound to.
     * @return array|false|string
     */
    public function getReceivingIp()
    {
        return $this->receivingIp;
    }

    /**
     * return the port that the server's Rx socket is listening on.
     * @return array|false|string
     */
    public function getReceivingPort()
    {
        return $this->receivingPort;
    }

    /**
     * return the IP address of the destination at the Tx socket is bound to.
     * @return array|false|string
     */
    public function getSendingIp()
    {
        return $this->sendingIp;
    }

    /**
     * return the port that the server's Tx socket will send to.
     * @return array|false|string
     */
    public function getSendingPort()
    {
        return $this->sendingPort;
    }
}