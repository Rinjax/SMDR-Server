<?php

namespace App\Managers;

use Carbon\Carbon;

class ServerManager
{
    protected $socket;

    protected $logFile;

    protected $ip;

    protected $port;

    public function __construct()
    {
        $this->ip = getenv('SMDR_IP');
        $this->port = getenv('SMDR_PORT');

        $this->logFile = $this->setLogFile();

        set_time_limit(0);

        $this->socket = stream_socket_server("udp://" . $this->ip .":" . $this->port, $errno, $errstr, STREAM_SERVER_BIND);
        if (!$this->socket) {
            die("$errstr ($errno)");
        }
    }

    public function run()
    {
        $packet = stream_socket_recvfrom($this->socket, 1500, 0);

        //$packet >>
    }

    public function writeToLogFile($packet)
    {
        try{
            file_put_contents($this->logFile, $packet, FILE_APPEND);
        }catch(\Exception $e){
            die($e->getMessage());
        }
    }

    public function setLogFile()
    {
        return APP_ROOT . '/Logs/' . Carbon::now()->format('Y-m-d') . '-SMDR.log';
    }
}