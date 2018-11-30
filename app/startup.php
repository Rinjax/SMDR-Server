<?php

require_once dirname(__FILE__) . '/../app/bootstrap.php';

$server = new \App\Managers\ServerManager();

echo $server->setLogFile();