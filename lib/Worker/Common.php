<?php
namespace Worker;

abstract class Common {
	protected $params, $client;

	public static function assign($msg, WebsocketClient $client, $requester)
	{
		if (!preg_match('/(.+)\((.+)\)/', $msg, $parts)) return false;
		$class = __NAMESPACE__ . '\\' . ucfirst($parts[1]);
		if (!class_exists($class) || !is_subclass_of($class, __CLASS__)) return false;
		$params = explode(',', $parts[2]);
		$worker = new $class($params, $client, $requester);
		$worker->start();
	}

	public function __construct(array $params, WebsocketClient $client, $requester)
	{
		$this->params = $params;
		$this->client = $client;
		$this->requester = $requester;
	}

	protected function output($data)
	{
		$this->client->sendData('RESULT|' . $this->requester . '|' . json_encode($data));
	}

	public function start()
	{
		$time = microtime(true);
		$this->run();
		$this->output(array(
			'time' => round(microtime(true) - $time, 2),
			'type' => 'end',
		));
	}

	abstract protected function run();
}
