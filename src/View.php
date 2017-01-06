<?php
namespace Pd;

use \Pd\Exception\NotViewFileException;
use \Pd\Exception\SystemException;

class View {

	private $viewFile;
	
	public $smarty;

	public function __construct() {
		$this->smarty = new \Smarty();
	}

	public function setViewFile($viewFile) {
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
		
		foreach ($data as $key => $value) {
			$this->smarty->assign($key, $value);
		}

		$this->smarty->display($this->viewFile);
	}

}
