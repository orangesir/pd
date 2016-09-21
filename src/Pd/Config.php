<?php
namespace Pd;

use Exception\SystemException;

class Config {

	private static $globalConfigs = array();

	public static function get($key) {
		return isset(self::$globalConfigs[$key]) ? self::$globalConfigs[$key]: null;
	}

	public static function set($key, $value) {
		if(!is_string($key)) {
			throw new SystemException("Config key not a String!");
		}
		self::$globalConfigs[$key] = $value;
	}

	public static function clean() {
		self::$globalConfigs = array();
	}

}