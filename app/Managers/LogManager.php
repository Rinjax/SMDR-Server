<?php

namespace App\Managers;

use Carbon\Carbon;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogManager
{
    protected $logger;

    protected $logLevel;

    public function __construct($channel)
    {
        $this->logger =  new Logger($channel);

        $this->logLevel = getenv('LOG_LEVEL');

        $this->logger->pushHandler(new StreamHandler(APP_ROOT . '/Logs/System/' . Carbon::now()->format('Y-m-d') . '.log', $this->logLevel));

    }

    public function debug($message, array $data = null)
    {
        $this->logger->debug($message, $data);
    }

    public function info($message, array $data = null)
    {
        $this->logger->info($message, $data);
    }

    public function warning($message, array $data = null)
    {
        $this->logger->warning($message, $data);
    }

    public function error($message, array $data = null)
    {
        $this->logger->error($message, $data);
    }
}