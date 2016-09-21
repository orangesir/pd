<?php
namespace tests;

use \Pd\Bdo;

class TestBdo extends TestCase {

	protected $dsn = "mysql:host=127.0.0.1;port=3306;dbname=pdtest;charset=utf8";
	protected $username = "root";
	protected $password = "";

	protected function createTable() {
		$createTableSql = <<<SQL
		CREATE TABLE dotable(
			`id` int primary key AUTO_INCREMENT,
			`name` char(20)
			);
SQL;
		$do = new Bdo($this->dsn, $this->username, $this->password);
		return $do->execute($createTableSql);
	}

	protected function dropTable() {
		$deleteDotableSql = "DROP TABLE IF EXISTS dotable;";
		$do = new Bdo($this->dsn, $this->username, $this->password);
		return $do->execute($deleteDotableSql);
	}

	protected function hasTable() {
		$tableSql = "SHOW TABLES LIKE '%dotable%'";
		$do = new Bdo($this->dsn, $this->username, $this->password);
		$tableList = $do->getCoL($tableSql);
		return in_array("dotable", $tableList);
	}
	
}