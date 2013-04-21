<?php
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Countdown\App;

require dirname(__DIR__) . '/lib/vendor/autoload.php';

$server = IoServer::factory(new WsServer(new App()), 8080);

$server->run();
