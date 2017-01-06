<?php
namespace TestRes;

use \Pd\Controller as BaseController;
use \Pd\Response;

class Controller extends BaseController {

	protected $_responseTypeMap = array(
		"testview" => Response::TYPE_VIEW,
		"testjson" => Response::TYPE_JSON 
		);
	
	protected $_viewFileMap = array(
		"testview" => "testView.php"
		);

	public function testJson() {
		return array("msg"=>"ok");
	}

	public function testView() {
		return array("testInfo"=>5);
	}
}
