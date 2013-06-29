<?php
namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Server implements MessageComponentInterface
{
	protected $clients, $worker, $pids;

	public function __construct($worker) {
		$this->clients = array();
		$this->worker = $worker;
		$this->pids = array();
	}

	public function onOpen(ConnectionInterface $conn) {
		// Store the new connection to send messages to later
		$this->clients[$conn->resourceId] = ($conn);

		echo "New connection! ({$conn->resourceId})\n";
	}

	public function onMessage(ConnectionInterface $from, $msg) {
		echo sprintf('Connection %d sending message "%s"' . "\n", $from->resourceId, $msg);

		if (preg_match('/^RESULT\|(\d+)\|(.+)$/', $msg, $matches)) {
			list(,$id,$data) = $matches;
			if (isset($this->clients[$id])) $this->clients[$id]->send($data);
		} elseif ($msg == 'KILL') {
			$this->kill($from->resourceId);
		} else {
			$cmd =
				'php ' . $this->worker . ' ' . escapeshellarg($msg) . ' ' . $from->resourceId .
				' > /dev/null 2>&1 & echo $! > /tmp/countdown.pid';
			exec($cmd);
			$this->pids[$from->resourceId] = (int)file_get_contents('/tmp/countdown.pid');
		}
	}

	public function onClose(ConnectionInterface $conn) {
		// The connection is closed, remove it, as we can no longer send it messages
		unset($this->clients[$conn->resourceId]);
		$this->kill($conn->resourceId);

		echo "Connection {$conn->resourceId} has disconnected\n";
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "An error has occurred: {$e->getMessage()}\n";

		$conn->close();
	}

	protected function kill($id)
	{
		if (empty($this->pids[$id])) return;
		exec('kill -9 ' . $this->pids[$id] . ' > /dev/null 2>&1');
		unset($this->pids[$id]);
	}
}
