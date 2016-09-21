<?php
namespace Pd;

class App {

	private $request;
	private $response;
	private $route;
	private $view;
	private $viewPath;
	private $appDir;

	public function handle() {

		$this->getRoute()->parse();

		$this->response = $this->getResponse();

		$contoller =  $this->getRoute()->getController();
		$contoller->_setResponse($this->response);
		$contoller->_setRequest($this->request);

		$method = $this->getRoute()->getMethod();

		$data = call_user_func(array($contoller, $method));
		$data = is_array($data) ? $data:array();

		$this->response->setType($contoller->_getResponseTypeMap($method));
		$this->response->setData($data);
		$this->response->setView($this->view);
		$this->response->setViewFile($this->getViewPath().DIRECTORY_SEPARATOR.$contoller->_getView($method));
		return $this->response;
	}

	public function getRoute() {
		if(!$this->route) {
			throw new Exception\SystemException("plase App:setRoute");
		}
		return $this->route;
	}

	public function setRoute(Route $route) {
		if(!$route) {
			throw new Exception\SystemException("App:setRoute input route must a object instanceof \Pd\Route");
		}
		$route->setApp($this);
		$this->route = $route;
		return $this;
	}

	public function setResponse(Response $response) {
		if(!$response) {
			throw new Exception\SystemException("App::setResponse input response must a object  instanceof \Pd\Response");
		}
		$this->response = $response;
	}

	public function getResponse() {
		if(!$this->response) {
			$this->response = new Response();
		}
		return $this->response;
	}

	public function getRequest() {
		if(!$this->request) {
			$this->request = self::currentRequest();
		}
		return $this->request;
	}

	public function setRequest(Request $request) {
		if(!$request) {
			throw new Exception\SystemException("App::setRequest input reqeust must a object  instanceof \Pd\Request");
		}
		$this->request = $request;
		return $this;
	}

	public function setView(View $view) {
		$this->view = $view;
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

	public static function currentRequest() {
		return new Request();
	}

}