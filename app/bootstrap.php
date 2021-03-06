<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Load the .ENV file
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

//set the application root
define('APP_ROOT', dirname(__FILE__), true);

if (!file_exists(APP_ROOT . '/Logs/SMDR')) {
    mkdir('path/to/directory', 0755, true);
}