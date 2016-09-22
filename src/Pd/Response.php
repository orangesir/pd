<?php
namespace Pd;

class Response {

	const TYPE_VIEW = 1;
	const TYPE_JSON = 2;

	private $type;
	private $data;
	private $viewFile;
	private $viewClass;

	public function setViewClass($viewClass) {
		$this->viewClass = $viewClass;
	}

	public function getView() {
		return $this->viewClass ? new $this->viewClass($this->viewFile): new View($this->viewFile);
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setData(array $data) {
		$this->data = $data;
	}

	public function setViewFile($viewFile) {
		$this->viewFile = $viewFile;
	}

	public function send() {
		$sendDataString = "";
		if($this->type===self::TYPE_JSON) {
			echo json_encode($this->data, true);
		} else {
			return $this->getView()->render($this->data);
		}
	}
}