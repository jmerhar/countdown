<?php
use Worker\Common as Worker;
use Worker\WebsocketClient;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$client = new WebsocketClient();
if (!$client->connect('aurora.merhar.si', 8080, '/')) exit;
Worker::assign($argv[1], $client, $argv[2]);
