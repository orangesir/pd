<?php
namespace tests;

class ViewTest extends \PHPUnit_Framework_TestCase {

	public function testView() {
		$view = new \Pd\View(__DIR__."/res/testView.php");

		$this->assertEquals($view->getViewFile(), __DIR__."/res/testView.php");

		$view->render(array(
			"info" => "hello!"
			));
	}

	public function testSmarty() {
		// $smarty = new \Smarty();
	}

}