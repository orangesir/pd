<?php
namespace tests;

class ViewTest extends TestCase {

	public function testView() {
		$view = new \Pd\View(__DIR__."/res/testView.php");

		$this->assertEquals($view->getViewFile(), __DIR__."/res/testView.php");

		$testInfo = $view->render(array(
			"testInfo"=>5
			));
		$this->assertEquals($testInfo, 5);
	}

}