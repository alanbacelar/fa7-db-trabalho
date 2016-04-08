<?php

class Connection
{
	private $database;
	private $pg_config;

	public function __construct($database) {
		$this->database = $database;
	}

	public function setPostgreSQLConfig($pg_config) {
		$this->pg_config = $pg_config;
	}

	public function getConnection() {
		switch ($this->database) {
			case 'mongodb':
				return $this->getMongoConnection(null, ['replicaSet' => 'myReplSetName', 'socketTimeoutMS' => -1]);
				break;

			case 'postgresql':
				return $this->getPDOConnection($this->pg_config);

			case 'sqlite':
				return $this->getPDOConnection('sqlite::memory:');
			
			default:
				die('Database connection not supported.');
		}
	}

	private function getMongoConnection() {
		return new MongoClient();
	}

	private function getPDOConnection($string_connection) {
		$pdo = new PDO( $string_connection, null, null, array(PDO::ATTR_PERSISTENT => true) );

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		return $pdo;
	}

}