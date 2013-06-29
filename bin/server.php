<?php
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Server;

require dirname(__DIR__) . '/vendor/autoload.php';

$worker = __DIR__ . '/worker.php';
$server = IoServer::factory(new WsServer(new Server($worker)), 8080);

$server->run();
