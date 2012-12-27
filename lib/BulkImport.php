<?php

class BulkImport {

	private $parser;
	private $table;
	private $fields;
	private $extra_sql;
	private $update = false;

	public function __construct($parser, $table) {
		if (!is_a($parser, 'Iterator')) throw new LogicException("Parser must be an Iterator");
		if (!is_string($table)) throw new LogicException("Table must be a string");

		$this->parser = $parser;
		$this->table = $table;
	}

	public function setExtraSql($extra_sql) {
		$this->extra_sql = $extra_sql;
	}

	public function setUpdate($update) {
		$this->update = $update;
	}

	public function getFields() {
		return $this->fields ?: $this->parser->getFields();
	}

	public function setFields($fields) {
		$this->fields = $fields;
	}

	protected function buildSql() {
		# build parameter list
		$param_sql = '';
		if (!empty($this->extra_sql)) $param_sql .= $this->extra_sql . ', ';
		$fields = $this->getFields();
		foreach($fields as $col) {
			$param_sql .= "$col = :$col, ";
		}
		$param_sql = rtrim($param_sql, ', ');

		# prepare for the import
		$sql = "insert into $this->table ";
		if (!$this->update) $sql .= 'ignore ';
		$sql .= 'set ';
		$sql .= $param_sql . ' ';
		if ($this->update) {
			$sql .= 'on duplicate key update ';
			$sql .= $param_sql . ' ';
		}

		return $sql;
	}

	public function run() {
		# use a new database connection, because this may overlap other queries
		$sql = $this->buildSql();
		$db = new_database();
		$stmt = $db->prepare($sql);

		foreach($this->parser as $row) {
			$stmt->execute($row);
			$stmt->closeCursor();
		}
	}

}

?>
