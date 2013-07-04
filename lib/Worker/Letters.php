<?php
namespace Worker;

class Letters extends Common {

	protected $words, $dictionary;

	protected function run()
	{
		$letters = $this->params;
		$this->words = array(); // store results here

		$this->dictionary = Dictionary::getInstance();

		foreach ($letters as &$letter) $letter = strtolower($letter);
		$powerset = array_unique($this->powerset($letters));
		usort($powerset, function($a, $b) { return strlen($a) > strlen($b); });
		$this->progress($this->getSteps($powerset));
		foreach ($powerset as $set) {
			if (strlen($set) < 5) continue;
			$this->permute_iterative($set);
		}
	}

	public function permute_iterative($string) // http://www.freewebs.com/permute/quickperm.html
	{
		$len = strlen($string);
		$ctrl = array_fill(0, $len, 0);
		$this->lookup($string);
		$this->progress();
		$i = 1;
		while ($i < $len) {
			if ($ctrl[$i] < $i) {
				$j = $i % 2 * $ctrl[$i];
				$this->progress();
				if ($string[$j] != $string[$i]) {
					$tmp = $string[$j];
					$string[$j] = $string[$i];
					$string[$i] = $tmp;
					$this->lookup($string);
				}
				$ctrl[$i]++;
				$i = 1;
			} else {
				$ctrl[$i] = 0;
				$i++;
			}
		}
	}

	function powerset($array)
	{
		$results = array('');
		foreach ($array as $j => $element) {
			$num = count($results);
			for($i = 0; $i < $num; $i++) {
				$result = $element . $results[$i];
				$split = str_split($result); sort($split); $result = implode('', $split); // sort
				if (!in_array($result, $results)) array_push($results, $result);
			}
		}
		return $results;
	}

	public function lookup($string)
	{
		if (!in_array($string, $this->words) && ($size = $this->dictionary->lookup($string))) {
			$this->words[] = $string;
			$info = $this->dictionary->info($string);
			$info['type'] = 'letters';
			$this->output($info);
		}
	}

	function factorial($in) {
		$out = 1;
		for ($i = 2; $i <= $in; $i++) $out *= $i;
		return $out;
	}

	function getSteps($powerset)
	{

		$cnt = count($powerset);
		$lengths = array();
		for ($i = 0; $i < $cnt; $i++) {
			$len = strlen($powerset[$i]);
			if ($len < 5) {
				unset($powerset[$i]);
				continue;
			}
			$lengths[$len]++;
		}
		$steps = 0;
		foreach ($lengths as $len => $cnt) $steps += $this->factorial($len) * $cnt;
		return $steps;
	}

}
