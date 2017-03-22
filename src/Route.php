<?php
namespace Pd;

use Pd\Exception\SystemException;

class Route {

	protected $request;
	protected $controller;
	protected $controllerClassString;
	protected $methodString;
	protected $configArray;

	public function getController() {
		if($this->controller) {
			return $this->controller;
		}
		if(!$this->controllerClassString) {
			throw new Exception\SystemException("please setControllerClassString!");
		}
		if(!is_string($this->controllerClassString)) {
			throw new Exception\SystemException("controllerClassString not a String!");
		}
		if(!class_exists($this->controllerClassString)) {
			throw new Exception\NotActionException($this->controllerClassString.":controller class is not find");
		}
		if(!is_subclass_of($this->controllerClassString,Controller::class)) {
			throw new Exception\NotActionException($this->controllerClassString." is not a \Pd\Controller!");
		}
		$this->controller = new $this->controllerClassString;
		return $this->controller;
	}

	public function getMethod() {
		if(!$this->methodString || !is_object($this->controller) || !method_exists($this->controller, $this->methodString)) {
			throw new Exception\NotActionException("method is not find");
		}
		return $this->methodString;
	}

	public function setControllerClassString($controllerClassString) {
		$this->controllerClassString = $controllerClassString;
	}

	public function setMethodString($methodString) {
		$this->methodString = $methodString;
	}

	public function setRequest(Request $request) {
		$this->request = $request;
	}

    /**
     * @return mixed
     */
    public function getControllerClassString()
    {
        return $this->controllerClassString;
    }

    /**
     * @return mixed
     */
    public function getMethodString()
    {
        return $this->methodString;
    }

    /**
     * @return mixed
     */
    public function getConfigArray()
    {
        return $this->configArray;
    }

    /**
     * @param mixed $configArray
     */
    public function setConfigArray($configArray)
    {
        $this->configArray = $configArray;
    }
	
	public function parse() {
		$this->controllerRelationMap($this->request);
	}

	/**
	 * make the rules for the relationship between request and controllerClassString-methodString
	 */
	public function controllerRelationMap(Request $request) {
		$uri = $request->uri();
		if($this->getConfigArray()) {
		    // actionString ===> controllerclass::$method
            foreach ($this->getConfigArray() as $regex => $actionString) {
                if($regex==$uri || preg_match($regex, $uri)) {
                    $actions = explode("::", trim($actionString));
                    if(count($actions)!=2) {
                        throw new SystemException("route config error:".$regex."=>".$actionString);
                    }
                    $this->setControllerClassString($actions[0]);
                    $this->setMethodString($actions[1]);
                    break;
                }
            }
        } else {
            if(trim($uri,"/")=="") {
                $this->setControllerClassString("\\Controller\\Home");
                $this->setMethodString("index");
            } else {
                $exUri = explode("/", trim($uri,"/"));

                $method = $exUri[count($exUri)-1];
                if(strpos($method, "_")===0) {
                    // 不允许访问_开头的方法
                    throw new \Pd\Exception\NotActionException();
                }
                $controllerString = "";
                for($i=0; $i<count($exUri)-1; $i++) {
                    $itemString = ucfirst($exUri[$i]);
                    $controllerString .= "\\".$itemString;
                }
                $this->setControllerClassString("\\Controller".$controllerString);
                $this->setMethodString($method);
            }
        }
	}
}
