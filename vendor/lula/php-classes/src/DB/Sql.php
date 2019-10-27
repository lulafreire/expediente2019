<?php 

namespace Lula\DB;

class Sql {

	const HOSTNAME = "127.0.0.1";
	const USERNAME = "root";
	const PASSWORD = "";
	const DBNAME = "db_portal";

	private $conn;

	public function __construct()
	{

		$this->conn = new \PDO("mysql:host=".Sql::HOSTNAME.";dbname=".Sql::DBNAME.";charset=utf8", Sql::USERNAME, Sql::PASSWORD,
		array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); // Permite a consulta a dados com caracteres especiais UTF-8

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

}

 ?>