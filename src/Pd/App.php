<?php
namespace Pd;

class App {

	private $request;
	private $response;
	private $route;
	private $viewPath;
	private $appDir;
	private $viewClass;

	public function handle() {

		$this->getRoute()->parse();

		$contoller =  $this->getRoute()->getController();
		$contoller->_setResponse($this->getResponse());
		$contoller->_setRequest($this->getRequest());

		$method = $this->getRoute()->getMethod();

		$data = call_user_func(array($contoller, $method));

		$this->getResponse()->setType($contoller->_getResponseTypeMap($method));
		$this->getResponse()->setData($data);
		$this->getResponse()->setViewClass($this->getViewClass());
		$this->getResponse()->setViewFile($this->getViewPath().DIRECTORY_SEPARATOR.$contoller->_getView($method));
		$this->getResponse()->send();
	}

	public function getViewClass() {
		return $this->viewClass;
	}

	public function setViewClass($viewClass) {
		$this->viewClass = $viewClass;
	}

	public function getRoute() {
		if(!$this->route) {
			$this->route = new Route();
		}
		return $this->route;
	}

	public function setRoute(Route $route) {
		$this->route = $route;
		return $this;
	}

	public function setResponse(Response $response) {
		$this->response = $response;
		return $this;
	}

	public function getResponse() {
		if(!$this->response) {
			$this->response = new Response();
		}
		return $this->response;
	}

	public function setRequest(Request $request) {
		$this->request = $request;
		return $this;
	}

	public function getRequest() {
		if(!$this->request) {
			$this->request = new Request();
		}
		return $this->request;
	}

	public function setViewPath($viewPath) {
		$this->viewPath = $viewPath;
	}

	public function getViewPath() {
		return $this->viewPath ?:$this->getAppDir().DIRECTORY_SEPARATOR."View";
	}

	public function setAppDir($appDir) {
		$this->appDir = $appDir;
	}

	public function getAppDir() {
		if(!$this->appDir) {
			throw new Exception\SystemException("please App::setAppDir");
		}
		return $this->appDir;
	}

}