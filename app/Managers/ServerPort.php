<?php

namespace App\Managers;

class ServerPort
{
    protected $socket;

    protected $logFile;

    protected $ip;

    protected $port;

    public function __construct()
    {

    }

    public function buildPort()
    {
        $this->ip = getenv('SMDR_IP');
        $this->port = getenv('SMDR_PORT');

        $this->socket = stream_socket_server("udp://" . $this->ip .":" . $this->port, $errno, $errstr, STREAM_SERVER_BIND);
        if (!$this->socket) {
            die("$errstr ($errno)");
        }

        return $this->socket;
    }

    public function run()
    {
        do{
            $packet = stream_socket_recvfrom($this->socket, 1500, 0);

            $this->writeToLogFile($packet);
        }

        while ($packet !== false);

    }

    public function writeToLogFile($packet)
    {
        try{
            file_put_contents($this->logFile, $packet . "\r\n", FILE_APPEND);
        }catch(\Exception $e){
            die($e->getMessage());
        }
    }

    public function writeToDatabase()
    {

    }

    public function setLogFile()
    {
        return APP_ROOT . '/Logs/' . Carbon::now()->format('Y-m-d') . '-SMDR.log';
    }
}