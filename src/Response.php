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
	private $bodyString = "";

	public function setViewClass($viewClass) {
		$this->viewClass = $viewClass;
	}

	public function getView() {
		return $this->viewClass ? new $this->viewClass($this->viewFile): new View($this->viewFile);
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function setViewFile($viewFile) {
		$this->viewFile = $viewFile;
	}

	public function make() {
		if($this->type===self::TYPE_JSON) {
			$this->bodyString = json_encode($this->data, true);
		} else if($this->type===self::TYPE_STRING){
			if(!is_string($this->data)) {
				throw new \Pd\Exception\SystemException("string response data not a string!");
			}
			$this->bodyString = $this->data;
		} else {
			ob_start();
			$this->getView()->render($this->data);
			$this->bodyString = ob_get_clean();
		}
	}

	public function __toString() {
		return $this->bodyString;
	}
}