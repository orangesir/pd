<?php
namespace tests;

use \Pd\App;

class AppTest extends TestCase {

	public function testView() {
		$_SERVER["REQUEST_URI"] = "/controller/testview";

		$route = new \TestRes\Route();
		$app = new App();
		$app->setRoute($route);
		$app->setAppDir(__DIR__."/res");
		$app->setViewPath(__DIR__."/res");
		$response = $app->run();
		$this->assertEquals($response->__toString(), "5testView");

		unset($_SERVER["REQUEST_URI"]);
	}

	public function testJson() {
		$_SERVER["REQUEST_URI"] = "/controller/testjson";

		$route = new \TestRes\Route();
		$app = new App();
		$app->setRoute($route);
		$app->setAppDir(__DIR__."/res");
		$app->setViewPath(__DIR__."/res");
		$response = $app->run();

		$this->assertEquals($response->__toString(), json_encode(array("msg"=>"ok"), true));

		unset($_SERVER["REQUEST_URI"]);
	}

}