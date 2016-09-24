<?php
namespace Pd;

use \Pd\Exception\NotViewFileException;

class View {

	private $viewFile;

	public function __construct($viewFile) {
		if(!is_string($viewFile)) {
			throw new NotViewFileException("View::__construct input viewFile is not a filename String");
		}
		$this->viewFile = $viewFile;
	}

	public function getViewFile() {
		return $this->viewFile;
	}

	public function render(array $data=array()) {
		if(!file_exists($this->viewFile)) {
			throw new NotViewFileException("not find view file:".$this->viewFile);
		}
		extract($data, EXTR_SKIP);
		include $this->viewFile;
	}
}