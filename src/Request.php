<?php
namespace Pd;

class Request {

	public function uri() {
		$uri = $_SERVER["REQUEST_URI"];
		return strpos($uri, "?")===false ? $uri : substr($uri, 0, strpos($uri, "?"));
	}

	public function query() {
		return isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"]:"";
	}

	public function hasGet($key) {
		return isset($_GET[$key]);
	}

	public function hasPost($key) {
		return isset($_POST[$key]);
	}

	public function get($key, $xssFilter=1) {
		$value = isset($_GET[$key]) ? $_GET[$key]:"";
		return $xssFilter ? $this->xssFilter($value) : $value;
	}

	public function post($key, $xssFilter=1) {
		$value = isset($_POST[$key]) ? $_POST[$key]:"";
		return $xssFilter ? $this->xssFilter($value) : $value;
	}

	public function xssFilter($value) {
		if(!is_array($value)) {
			return htmlspecialchars($value, ENT_QUOTES);
		}
		foreach ($value as $key => &$item) {
			$item = htmlspecialchars($item, ENT_QUOTES);
		}
		return $value;
	}
	
}
