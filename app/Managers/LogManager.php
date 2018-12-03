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

    /**
     * Log a debug entry to the log file.
     * @param $message
     * @param array|null $data
     */
    public function debug($message, array $data = [])
    {
        $this->logger->debug($message, $data);
    }

    /**
     * Log a info entry to the log file.
     * @param $message
     * @param array|null $data
     */
    public function info($message, array $data = [])
    {
        $this->logger->info($message, $data);
    }

    /**
     * Log a warning to the log file.
     * @param $message
     * @param array|null $data
     */
    public function warning($message, array $data = [])
    {
        $this->logger->warning($message, $data);
    }

    /**
     * Log an error to the log file.
     * @param $message
     * @param array|null $data
     */
    public function error($message, array $data = [])
    {
        $this->logger->error($message, $data);
    }
}