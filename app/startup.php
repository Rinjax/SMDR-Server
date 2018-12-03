<?php

require_once dirname(__FILE__) . '/../app/bootstrap.php';

set_time_limit(0);

$server = new \App\Server();

$server->run();