<?php
namespace tests\DB;

use \tests\TestBdo;

class TableTest extends TestBdo {

	public function testTable() {

		if($this->hasTable()) {
			$this->dropTable();
		}
		$this->assertFalse($this->hasTable());
		$this->assertTrue($this->createTable());
		$this->assertTrue($this->hasTable());
		$this->assertTrue($this->dropTable());
		$this->assertFalse($this->hasTable());
	}
	
}