<?php
namespace TestRes;

use \Pd\Route as BaseRoute;
use \Pd\Request;

class Route extends BaseRoute {

	public function controllerRelationMap(Request $request) {
		$uri = $request->uri();
		$exUri = explode("/", trim($uri,"/"));
		$method = $exUri[count($exUri)-1];
		$controllerString = "\\TestRes";
		for($i=0; $i<count($exUri)-1; $i++) {
			$itemString = ucfirst($exUri[$i]);
			$controllerString .= "\\".$itemString;
		}
		$this->setControllerClassString($controllerString);
		$this->setMethodString($method);
	}

}
