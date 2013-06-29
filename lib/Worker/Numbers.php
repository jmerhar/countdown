<?php
namespace Worker;

class Numbers extends Common {

	protected $index, $diff, $ops, $target, $results;

	protected function run()
	{
		$this->target = array_shift($this->params);
		$numbers = $this->params;

		sort($numbers);
		$this->ops = array('a', 'm', 's', 'd');
		$this->diff = 100;
		$this->index = 1;
		$this->go(array(), $numbers, array());
	}

	function not($v, $a, $b) { return (abs($a) != $v) && (abs($b) != $v); }

	function a($a, $b) { return $this->not(0, $a, $b) ? ($a + $b) : null; }
	function s($a, $b) { return $this->not(0, $a, $b) ? ($a - $b) : null; }
	function m($a, $b) { return $this->not(1, $a, $b) ? ($a * $b) : null; }
	function d($a, $b) { return ($b && (($a % $b) == 0) && $this->not(1, $a, $b)) ? ($a / $b) : null; }

	function go($stack, $numbers, $expr)
	{
		$cnt = count($stack);
		if ($cnt == 1) {
			$res = $stack[0];
			$d = abs($res - $this->target);
			if (($d == 0) || ($d < $this->diff)) {
				$postfix = strtr(implode(' ', $expr), 'asmd', '+-*/');
				$infix = $this->infix($expr) . ' = ' . $res;
				if (!in_array($postfix, $this->results) && !in_array($infix, $this->results)) {
					$this->output(array(
						'postfix' => $postfix,
						'infix'   => $infix,
						'delta'   => $d,
						'index'   => $this->index++,
						'type'    => 'numbers',
					));
					$this->diff = $d;
					$this->results[] = $infix;
					$this->results[] = $postfix;
				}
				//if ($this->diff == 0) done();
			}
		} elseif ($cnt > 1) {
			foreach ($this->ops as $op) {
				$nstack = $stack;
				$second = array_pop($nstack);
				$first  = array_pop($nstack);
				if ($first < $second) continue;
				$res = $this->$op($first, $second);
				if (is_null($res)) continue;
				array_push($nstack, $res);
				$nexpr = $expr;
				array_push($nexpr, $op);
				$this->go($nstack, $numbers, $nexpr);
			}
		}
		$cnt = count($numbers);
		for ($i = 0; $i < $cnt; $i++) {
			$item = array_pop($numbers);
			$nstack = $stack;
			array_push($nstack, $item);
			$nexpr = $expr;
			array_push($nexpr, $item);
			$this->go($nstack, $numbers, $nexpr);
			array_unshift($numbers, $item);
		}
	}

	function infix($expr)
	{
		$stack = array();
		foreach ($expr as $item) {
			if (is_numeric($item)) {
				array_push($stack, $item);
			} else {
				if (count($stack) < 2) trigger_error('Error parsing postfix expression');
				$second = array_pop($stack);
				$first  = array_pop($stack);
				$cf = preg_replace('/\(.+\)/', '', $first);
				$cs = preg_replace('/\(.+\)/', '', $second);
				switch ($item) {
					case 'm':
						if (strpbrk($cf, 'as')) $first = "($first)";
					case 's':
						if (strpbrk($cs, 'as')) $second = "($second)";
						break;
					case 'd':
						if (strpbrk($cf, 'asd')) $first = "($first)";
						if (strpbrk($cs, 'asdm')) $second = "($second)";
						break;
				}
				array_push($stack, "$first $item $second");
			}
		}
		if (count($stack) != 1) trigger_error('Error parsing postfix expression');
		$result = strtr(current($stack), 'asmd', '+-*/');

		return $result;
	}

}
