<?php

abstract class MappingIterator extends IteratorIterator {

	abstract public function map($key, $value);

	public function __construct($iterator) {
		parent::__construct($iterator);
	}

	public function current() {
		return $this->map(parent::key(), parent::current());
	}

}

?>
