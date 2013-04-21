<?php
use Ratchet\Server\IoServer;
use Countdown\App;

require dirname(__DIR__) . '/lib/vendor/autoload.php';

$server = IoServer::factory(new App(), 8080);

$server->run();
