<?php

class CsvIterator implements Iterator {

	private $allowed_fields;
	private $input;
	private $fields;
	private $position = -1;

	public function __construct($source) {
		$this->input = fopen($source, 'r');
		$this->readHeader();
	}

	public function __destruct() {
		fclose($this->input);
	}

	public function getFields() {
		return $this->fields;
	}

	public function readHeader() {
		$this->fields = fgetcsv($this->input, 1024);
		$this->validateHeader();
	}

	public function rewind() {
		if ($this->position > -1) {
			rewind($this->input);
			$this->position = -1;
			$this->readHeader();
		}

		$this->next();
	}

	public function key() {
		return $this->position;
	}

	public function current() {
		return $this->row;
	}

	public function next() {
		$this->row = null;
		$values = fgetcsv($this->input, 1024);
		if ($values) $this->row = array_combine($this->fields, $values);
		$this->position++;
		return $this->row;
	}

	public function valid() {
		return !is_null($this->row);
	}

	public function setAllowedFields($allowed_fields) {
		if (is_string($allowed_fields)) {
			$allowed_fields = explode(',', $allowed_fields);
		}
		$this->allowed_fields = $allowed_fields;
	}

	protected function validateHeader() {
		if (isset($this->allowed_fields)) {
			$disallowed_fields = array_diff($this->fields, $this->allowed_fields);
			if (count($disallowed_fields) != 0) {
				header('HTTP/1.0 403 Forbidden');
				die("Disallowed fields: " . implode(',', $disallowed_fields));
			}
		}
	}

}

?>
