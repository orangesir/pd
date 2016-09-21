<?php
namespace tests;

use \Pd\App;

class AppTest extends TestCase {

	public function testView() {
		$_SERVER["REQUEST_URI"] = "/controller/testview";

		$route = new \TestRes\Route();
		$app = new App();
		$app->setRoute($route);
		$app->setViewPath(__DIR__."/res");
		$response = $app->handle();
		$testInfo = $response->send();
		$this->assertEquals($testInfo, 5);

		unset($_SERVER["REQUEST_URI"]);
	}

	public function testJson() {
		$_SERVER["REQUEST_URI"] = "/controller/testjson";

		$route = new \TestRes\Route();
		$app = new App();
		$app->setRoute($route);
		$app->setViewPath(__DIR__."/res");
		$response = $app->handle();
		ob_start();
		$response->send();
		$testInfo = ob_get_contents();
		ob_end_clean();

		$this->assertEquals($testInfo, json_encode(array("msg"=>"ok"), true));

		unset($_SERVER["REQUEST_URI"]);
	}

}