<?php
namespace Pd;

class Response {

	const TYPE_VIEW = 1;
	const TYPE_JSON = 2;
	const TYPE_STRING = 3;

	private $type;
	private $data;
	private $viewFile;
	private $viewClass;
	private $view;
	private $bodyString = "";

	public function setViewClass($viewClass) {
		$this->viewClass = $viewClass;
	}

	public function getView() {
		if($this->view) {
			return $this->view;
		}
		$this->view = $this->viewClass ? new $this->viewClass(): new View();
		return $this->view;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setData($data) {
        if($this->data && is_array($this->data)) {
		    $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }
	}

	public function setViewFile($viewFile) {
		$this->viewFile = $viewFile;
	}

	public function make() {
		$this->getView()->setViewFile($this->viewFile);
		if($this->type===self::TYPE_JSON) {
			$this->bodyString = json_encode($this->data, true);
		} else if($this->type===self::TYPE_STRING){
			if(!is_string($this->data)) {
				throw new \Pd\Exception\SystemException("string response data not a string!");
			}
			$this->bodyString = $this->data;
		} else {
            if(!is_array($this->data)) {
				throw new \Pd\Exception\SystemException("smarty view response data not a array!");
            }
			ob_start();
			$this->getView()->render($this->data);
			$this->bodyString = ob_get_clean();
		}
	}

	public function __toString() {
		return $this->bodyString;
	}
}
