<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'CsvIterator.php';

class CsvIteratorTest extends PHPUnit_Framework_TestCase {

	private $csvfile;

	public function __construct() {
		$this->csvfile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'csvfile.csv';
	}

	public function testCsv() {
		$parser = new CsvIterator($this->csvfile);
		$count = 0;
		foreach($parser as $key => $row) {
			switch ($count) {
			case 0:
				$this->assertEquals($key, 0);
				$this->assertEquals($row, array('a'=>'1', 'b'=>'2', 'c'=>'3'));
				break;
			case 1:
				$this->assertEquals($key, 1);
				$this->assertEquals($row, array('a'=>'4', 'b'=>'5', 'c'=>'6'));
				break;
			}
			$count++;
		}
		$this->assertEquals($count, 2);
	}

	public function testAllowedFields() {
		$parser = new CsvIterator($this->csvfile);
		$parser->setRequiredFields(array('a', 'b', 'c'));
		foreach($parser as $key => $row);
	}

}

?>
