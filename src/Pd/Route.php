<?php
namespace Pd;

abstract class Route {

	protected $app;

	protected $request;
	protected $controller;
	protected $method;

	protected $controllerClassString;
	protected $methodString;

	public function getController() {
		if($this->controller) {
			return $this->controller;
		}
		if(!$this->controllerClassString 
			|| !class_exists($this->controllerClassString) 
			|| !is_subclass_of($this->controllerClassString,Controller::class)) {
			throw new Exception\NotActionException("controller is not find");
		}
		$this->controller = new $this->controllerClassString;
		return $this->controller;
	}

	public function getMethod() {
		if($this->method) {
			return $this->method;
		}
		if(!$this->methodString || !is_object($this->controller) || !method_exists($this->controller, $this->methodString)) {
			throw new Exception\NotActionException("method is not find");
		}
		$this->method = $this->methodString;
		return $this->method;
	}

	public function setControllerClassString($controllerClassString) {
		if(!$controllerClassString || !is_string($controllerClassString)) {
			throw new Exception\SystemException("setControllerClassString input controllerClassString is emptyString or not a String");
		}
		$this->controllerClassString = $controllerClassString;
	}

	public function setMethodString($methodString) {
		if(!$methodString || !is_string($methodString)) {
			throw new Exception\SystemException("setMethodString input methodString is emptyString or not a String");
		}
		$this->methodString = $methodString;
	}

	public function setApp(App $app) {
		$this->app = $app;
		$this->request = $app->getRequest();
	}
	
	public function parse() {
		if(!$this->app) {
			throw new Exception\SystemException("please Route:setApp");
		}
		$this->controllerRelationMap($this->request);
	}

	/**
	 * make the rules for the relationship between request and controllerClassString-methodString
	 */
	abstract public function controllerRelationMap(Request $request);
}