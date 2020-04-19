<?php 

class Sql 
{
	const HOSTNAME = "revistasocial.cim4doext4zz.sa-east-1.rds.amazonaws.com:3306";
	const USERNAME = "revistadigital";
	const PASSWORD = "adm1n2020";
	const DBNAME = "revistasocial";

	private $conn;

	public function __construct()
	{
		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME.";charset=utf8;", 
			Sql::USERNAME,
			Sql::PASSWORD
		);
	}

	private function setParams($stmt, $parameters = array())
	{
		foreach ($parameters as $key => $value)
		{
			$stmt->bindValue($key, $value);
		}
	}

	public function query($rawQuery, $params = array())
	{
		try
		{
			$stmt = $this->conn->prepare($rawQuery);

			$this->conn->beginTransaction();

			$this->setParams($stmt, $params);

			$this->conn->commit();

			return ['success' => $stmt->execute(), 'status' => $this->conn];
		}
		catch(Exception $error)
		{
			$conn->rollback();

			throw new Exception('Message error: ' . $error->getMessage());
		}
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