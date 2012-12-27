<?php

class DatabaseException extends Exception {

	private $sql;
	private $params;

	public function __construct($message, $sql = null, $params = null) {
		parent::__construct($message);
		$this->sql = $sql;
		$this->params = $params;
	}

}

?>
