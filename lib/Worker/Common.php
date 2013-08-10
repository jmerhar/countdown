<?php
namespace Worker;

abstract class Common {
	protected $params, $client, $answers = 0, $step = 0, $steps = 0, $factor = 0;

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
		if (in_array($data['type'], array('letters', 'numbers'))) $this->answers++;
		$this->client->sendData('RESULT|' . $this->requester . '|' . json_encode($data));
	}

	public function start()
	{
		$time = microtime(true);
		$this->run();
		$this->output(array(
			'time'    => round(microtime(true) - $time, 2),
			'type'    => 'end',
			'answers' => $this->answers,
		));
	}

	protected function progress($steps = null)
	{
		if (is_null($steps)) {
			if (!$this->steps) return;
			$this->step++;
			if (($this->step % $this->factor) == 0) {
				$this->output(array(
					'type'    => 'progress',
					'percent' => round($this->step / $this->steps * 100),
				));
			}
		} else {
			$this->steps = $steps;
			$this->factor = ceil($this->steps / 50);
		}
	}

	abstract protected function run();
}
