<?php
namespace Pd;

/**
 * Base DB Object:use PDO to use DDL/DML
 * use Bdo because Do is php keyword
 * @throw \PDOException 
 */
class Bdo {
	
	public static $pdoPool = array();
	public static $pdoStatementPool = array();

	private $pdo;
	private $pdoKey;

	/**
	 * @param dsn:
	 *     example: "mysql:host=127.0.0.1;port=3306;dbname=testDb;charset=utf-8"
	 *     mysqldoc: http://php.net/manual/zh/ref.pdo-mysql.connection.php
	 * @param username
	 * @param password
	 * 
	 */
	public function __construct($dsn, $username, $password) {
		$this->pdoKey = $dsn.$username.$password;
		if(!isset(self::$pdoPool[$this->pdoKey])) {
			self::$pdoPool[$this->pdoKey] = new \PDO($dsn, $username, $password);
			self::$pdoPool[$this->pdoKey]->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
			self::$pdoPool[$this->pdoKey]->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES,true);
			self::$pdoStatementPool[$this->pdoKey] = array();
		}
		$this->pdo = self::$pdoPool[$this->pdoKey];
	}

	/**
	 * @param $stateSql sql语句:select * from info where id=?
	 * @param $varList array($param1, $param2)
	 * @return bool 成功true,失败false
	 */
	public function execute($stateSql,array $varList=array()) {
		$statement = $this->getStatement($stateSql);
		$result = $statement->execute($varList);
		if($result) {
			return true;
		} else {
			throw new \Pd\Exception\DoException("execute sql fail");
		}
	}

	/**
	 * 受statement上一条sql语句影响的行数
	 */
	public function rowCount($stateSql) {
		$statement = $this->getStatement($stateSql);
		return $statement->rowCount();
	}

	/**
	 * 主键不自增时返回 0
	 * 主键自增,返回最后插入行的ID或序列值
	 */
	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}

	public function getRows($stateSql,array $varList=array(), $key=null) {
		$statement = $this->getStatement($stateSql);
		$result = $statement->execute($varList);
		if($result) {
			$rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
			if($key) {
				$filterRows = array();
				foreach ($rows as $row) {
					$filterRows[$row[$key]] = $row;
				}
				$rows = $filterRows;
			}
			$statement->closeCursor();
			return $rows;
		} else {
			$statement->closeCursor();
			throw new \Pd\Exception\DoException("execute sql fail");
		}
	}

	public function getRow($stateSql,array $varList=array()) {
		$statement = $this->getStatement($stateSql);
		$result = $statement->execute($varList);
		if($result) {
			$row = $statement->fetch(\PDO::FETCH_ASSOC);
			$statement->closeCursor();
			return $row?:array();
		} else {
			$statement->closeCursor();
			throw new \Pd\Exception\DoException("execute sql fail");
		}
	}

	/**
	 * 获取列组成list返回
	 */
	public function getCol($stateSql,array $varList=array()) {
		$statement = $this->getStatement($stateSql);
		$result = $statement->execute($varList);
		if($result) {
			$col = $statement->fetchAll(\PDO::FETCH_COLUMN);
			$statement->closeCursor();
			return $col;
		} else {
			$statement->closeCursor();
			throw new \Pd\Exception\DoException("execute sql fail");
		}
	}

	/**
	 * 查询所有第一行的第一个字段
	 */
	public function getOne($stateSql,array $varList=array()) {
		$statement = $this->getStatement($stateSql);
		$result = $statement->execute($varList);
		if($result) {
			$row = $statement->fetch(\PDO::FETCH_NUM);
			$statement->closeCursor();
			return $row ? $row[0] : false;
		} else {
			$statement->closeCursor();
			throw new \Pd\Exception\DoException("execute sql fail");
		}
	}

	private function getStatement($stateSql) {
		$stateKey = trim($stateSql);
		if(!isset(self::$pdoStatementPool[$this->pdoKey][$stateKey])) {
			self::$pdoStatementPool[$this->pdoKey][$stateKey] = $this->pdo->prepare($stateSql);
		}
		return self::$pdoStatementPool[$this->pdoKey][$stateKey];
	}



}