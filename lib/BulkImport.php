<?php

class BulkImport {

	private $parser;
	private $table;
	private $fields;
	private $extra_sql;
	private $update = false;
	private $system_specific = false;
	private $dated = false;

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

	public function setSystemSpecific($system_specific) {
		$this->system_specific = $system_specific;
	}

	public function setDated($dated) {
		$this->dated = $dated;
	}

	protected function buildSql() {
		# build parameter list
		$param_sql = '';
		if (!empty($this->extra_sql)) $param_sql .= $this->extra_sql . ', ';
		if ($this->system_specific) {
			$system_id = get_system_id();
			$param_sql .= "system_id = $system_id, ";
		}
		$fields = $this->getFields();
		foreach($fields as $col) {
			$param_sql .= "$col = :$col, ";
		}
		$param_sql = rtrim($param_sql, ', ');

		# prepare for the import
		$sql = "insert into $this->table ";
		if (!$this->update) $sql .= 'ignore ';
		$sql .= 'set ';
		if ($this->dated == true) $sql .= 'created = now(), modified = now(), ';
		$sql .= $param_sql . ' ';
		if ($this->update) {
			$sql .= 'on duplicate key update ';
			if ($this->dated == true) $sql .= 'modified = now(), ';
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
