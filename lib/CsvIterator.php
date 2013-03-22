<?php

class CsvIterator implements Iterator {

	private $required_fields = null;
	private $optional_fields = null;
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
		if ($values) {
			if (count($values) != count($this->fields)) {
				throw new ValidationException("Wrong number of fields " . count($values) . " expected " . count($this->fields));
			}
			$this->row = array_combine($this->fields, $values);
		}
		$this->position++;
		return $this->row;
	}

	public function valid() {
		return !is_null($this->row);
	}

	public function setRequiredFields($required_fields) {
		$this->required_fields = $required_fields;
	}

	public function setOptionalFields($optional_fields) {
		$this->optional_fields = $optional_fields;
	}

	public function getAllowedFields() {
		# if neither constraint is specified, allow everything
		if (is_null($this->required_fields) && is_null($this->optional_fields)) {
			return null;
		}

		$allowed_fields = array();
		if (!is_null($this->required_fields)) $allowed_fields = array_merge($allowed_fields, $this->required_fields);
		if (!is_null($this->optional_fields)) $allowed_fields = array_merge($allowed_fields, $this->optional_fields);
		$allowed_fields = array_unique($allowed_fields);

		return $allowed_fields;
	}

	protected function validateHeader() {
		if (!is_null($this->required_fields)) {
			$missing_fields = array_diff($this->required_fields, $this->fields);
			if (count($missing_fields) != 0) {
				throw new ValiationException("Missing required fields: " . implode(',', $missing_fields));
			}
		}

		$allowed_fields = $this->getAllowedFields();
		if (!is_null($allowed_fields)) {
			$disallowed_fields = array_diff($this->fields, $allowed_fields);
			if (count($disallowed_fields) != 0) {
				throw new ValiationException("Disallowed fields: " . implode(',', $disallowed_fields));
			}
		}
	}

}

?>
