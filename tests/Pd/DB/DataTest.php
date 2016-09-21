<?php
namespace tests\DB;

use \tests\TestBdo;
use \Pd\Bdo;

class DataTest extends TestBdo {

	public function setUp() {
		$this->dropTable();
		$this->createTable();
	}

	public function tearDown() {
		$this->dropTable();
	}

	public function testInsert() {
		$bdo = new Bdo($this->dsn, $this->username, $this->password);
		$insertDataOneSql = "INSERT INTO dotable(`id`,`name`) VALUES(?, ?)";

		$this->assertTrue($bdo->execute($insertDataOneSql, array(1, "name1")));
		$this->assertEquals($bdo->rowCount($insertDataOneSql), 1);
		$this->assertEquals($bdo->lastInsertId(), 1);
		
		$insertDataTwoSql = "INSERT INTO dotable(`id`,`name`) VALUES(?,?),(?,?)";
		$this->assertTrue($bdo->execute($insertDataTwoSql, array(2, "name2", 3, "name3")));
		$this->assertEquals($bdo->rowCount($insertDataTwoSql), 2);
		$this->assertEquals($bdo->lastInsertId(), 3);
		$this->assertEquals($bdo->rowCount($insertDataOneSql), 1);

		$deleteDataSql = "DELETE FROM dotable WHERE id=?";
		$this->assertTrue($bdo->execute($deleteDataSql, array(1)));
		$this->assertTrue($bdo->execute($deleteDataSql, array(1)));

		$updateDataSql = "UPDATE dotable SET name=? WHERE id=?";
		$this->assertTrue($bdo->execute($updateDataSql, array("name1m",1)));
		$this->assertEquals($bdo->rowCount($updateDataSql), 0);
		$this->assertTrue($bdo->execute($updateDataSql, array("name2m",2)));
		$this->assertEquals($bdo->rowCount($updateDataSql), 1);
	}

	public function testGetRows() {

		$this->dropTable();
		$this->createTable();

		$bdo = new Bdo($this->dsn, $this->username, $this->password);
		$insertDataThreeSql = "INSERT INTO dotable(`id`,`name`) VALUES(?,?),(?,?),(?,?)";
		$this->assertTrue($bdo->execute($insertDataThreeSql,array(1, "name1", 2, "name2", 3, "name3")));
		// 查询正常数据
		$selectSql = "SELECT * FROM dotable";
		$this->assertEquals($bdo->getRows($selectSql), array(
			array("id"=>1,"name"=>"name1"),
			array("id"=>2,"name"=>"name2"),
			array("id"=>3, "name" => "name3")
			)
		);
		$this->assertEquals($bdo->getRows($selectSql, array(),"id"), array(
			1=>array("id"=>1,"name"=>"name1"),
			2=>array("id"=>2,"name"=>"name2"), 
			3=>array("id"=>3, "name" => "name3")
			));
		// 查询空值
		$selectSqlById = "SELECT * FROM dotable WHERE id=?";
		$this->assertEquals($bdo->getRows($selectSqlById, array(1)), array(array("id"=>1,"name"=>"name1")));
		$this->assertEquals($bdo->getRows($selectSqlById, array(5)), array());

	}

	public function testGetRow() {
		$this->dropTable();
		$this->createTable();

		$bdo = new Bdo($this->dsn, $this->username, $this->password);
		$insertDataThreeSql = "INSERT INTO dotable(`id`,`name`) VALUES(?,?),(?,?),(?,?)";
		$this->assertTrue($bdo->execute($insertDataThreeSql, array(1, "name1", 2, "name2", 3, "name3")));

		$selectSql = "SELECT * FROM dotable";
		$this->assertEquals($bdo->getRow($selectSql), array("id"=>1,"name"=>"name1"));

		$selectSqlById = "SELECT * FROM dotable WHERE id=?";
		$this->assertEquals($bdo->getRow($selectSqlById, array(1)), array("id"=>1,"name"=>"name1"));

		$deleteDataSql = "DELETE FROM dotable WHERE id=?";
		$this->assertTrue($bdo->execute($deleteDataSql, array(1)));
		$this->assertEquals($bdo->getRow($selectSqlById, array(1)), array());

		$updateDataSql = "UPDATE dotable SET name=? WHERE id=?";
		$this->assertTrue($bdo->execute($updateDataSql, array("name2m",2)));
		$this->assertEquals($bdo->getRow($selectSqlById, array(2)), array("id"=>2,"name"=>"name2m"));

	}

	public function testGetCol() {
		$this->dropTable();
		$this->createTable();

		$bdo = new Bdo($this->dsn, $this->username, $this->password);
		$selectSql = "SELECT id FROM dotable";
		$this->assertEquals($bdo->getCol($selectSql), array());

		$insertDataThreeSql = "INSERT INTO dotable(`id`,`name`) VALUES(?,?),(?,?),(?,?)";
		$this->assertTrue($bdo->execute($insertDataThreeSql,array(1, "name1", 2, "name2", 3, "name3")));

		$selectSql = "SELECT id FROM dotable";
		$this->assertEquals($bdo->getCol($selectSql, array()), array(1,2,3));

		$selectSqlTwoCol = "SELECT id,name FROM dotable";
		$this->assertEquals($bdo->getCol($selectSqlTwoCol, array()), array(1,2,3));

		$selectSqlAllCol = "SELECT * FROM dotable";
		$this->assertEquals($bdo->getCol($selectSqlAllCol, array()), array(1,2,3));

		$selectSqlRow = "SELECT * FROM dotable WHERE id=?";
		$this->assertEquals($bdo->getCol($selectSqlRow, array(3)), array(3));
	}

	public function testGetOne() {
		$this->dropTable();
		$this->createTable();

		$bdo = new Bdo($this->dsn, $this->username, $this->password);
		$insertDataThreeSql = "INSERT INTO dotable(`id`,`name`) VALUES(?,?),(?,?),(?,?)";
		$this->assertTrue($bdo->execute($insertDataThreeSql,array(1, "name1", 2, "name2", 3, "name3")));

		$selectSqlRow = "SELECT * FROM dotable WHERE id=?";
		$this->assertEquals($bdo->getOne($selectSqlRow, array(3)), 3);

		$selectSqlName = "SELECT name FROM dotable WHERE id=?";
		$this->assertEquals($bdo->getOne($selectSqlName, array(3)), "name3");
		$this->assertEquals($bdo->getOne($selectSqlName, array(4)), false);
	}
	
}