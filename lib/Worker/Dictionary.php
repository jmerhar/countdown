<?php
namespace Worker;
use \PDO;

class Dictionary extends PDO {

	private static $instance;
	private $words, $statement;

	public function __construct()
	{
		parent::__construct('sqlite:data/words.db');
		$this->words = $this->query("SELECT word, size FROM dictionary")->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_UNIQUE);
		$this->statement = $this->prepare("SELECT * FROM dictionary WHERE word = ?");
	}

	public static function getInstance() {
		if (self::$instance === null) self::$instance = new self();
		return self::$instance;
	}

	public function lookup($string)
	{
		return (isset($this->words[$string])) ? $this->words[$string] : null;
	}

	public function info($word)
	{
		$this->statement->execute(array($word));
		$item = $this->statement->fetch(PDO::FETCH_ASSOC);
		return array(
			'word'    => $item['display'] ?: $item['word'],
			'size'    => (int)$item['size'],
			'variant' => str_repeat('*', $item['variant']),
			'sort'    => (int)((100 - $item['size']) . strlen($item['word'])),
		);
	}

}
