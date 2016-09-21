<?php
namespace tests;

use \Pd\Config;

class ConfigTest extends TestCase {

	public function testBase() {
		Config::clean();
		$this->assertNull(Config::get("key1"));
		Config::set("key1", "value1");
		$this->assertEquals(Config::get("key1"), "value1");
	}

}