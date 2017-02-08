<?php

class Database {
	private $conn;
	private $open = false;

	function __construct() {
		$this->init();
	}
	function init() {
		$this->open();
	}

	function query($sql) {
		$stm = $this->conn->prepare($sql);
		if ($stm->execute()) {
			return $stm->fetchAll();
		} else {
			print_r($stm->errorInfo());
		}
		return false;
	}

	function close(){
		if($this->open){
			$this->conn = null;
			$this->open = false;
		}
	}
	function open(){
		if(!$this->open){
			$host = Tifa::$settings->database->host;
			$database_name = Tifa::$settings->database->database_name;
			$username = Tifa::$settings->database->username;
			$password = Tifa::$settings->database->password;
			if ($host && $database_name && $username && $password && !$this->open) {
				try {
					$dsn = "mysql:host=" . $host . ";dbname=" . $database_name . ";";
					$this->conn = new PDO($dsn, $username, $password);
					$this->open = true;
				} catch (PDOException $e) {
					echo 'Unable to connect: \n';
					print_r($e->getMessage());
				}
			}
		}
	}
}

?>