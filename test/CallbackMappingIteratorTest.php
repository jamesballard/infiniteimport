<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'CallbackMappingIterator.php';

class CallbackMappingIteratorTest extends PHPUnit_Framework_TestCase {

	public function testMapping() {
		$data = array(1, 2, 3);
		$inner = new ArrayIterator($data);

		$iterator = new CallbackMappingIterator($inner, function($key, $value) {
			return $value * 2;
		});

		$iterator->rewind();
		$this->assertTrue($iterator->valid());
		$this->assertEquals($iterator->current(), 2);
		$iterator->next();
		$this->assertTrue($iterator->valid());
		$this->assertEquals($iterator->current(), 4);
		$iterator->next();
		$this->assertTrue($iterator->valid());
		$this->assertEquals($iterator->current(), 6);
		$iterator->next();
		$this->assertFalse($iterator->valid());
	}

}

?>
