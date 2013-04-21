<?php
namespace Countdown;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class App implements MessageComponentInterface {
	public function onMessage(ConnectionInterface $client, $msg) {
		echo "$msg\n";
		$client->send($msg);
	}

    public function onOpen(ConnectionInterface $conn) {
    }

	public function onClose(ConnectionInterface $conn) {
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "An error has occurred: {$e->getMessage()}\n";

		$conn->close();
	}
}
