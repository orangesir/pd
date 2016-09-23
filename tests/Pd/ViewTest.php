<?php
namespace tests;

class ViewTest extends TestCase {

	public function testView() {
		$view = new \Pd\View(__DIR__."/res/testView.php");

		$this->assertEquals($view->getViewFile(), __DIR__."/res/testView.php");

		ob_start();
		$testInfo = $view->render(array(
			"testInfo"=>5
			));
		$viewString = ob_get_clean();
		$this->assertEquals($viewString, "5testView");
	}

}