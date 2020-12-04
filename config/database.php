<?php
	class Database{
			//database credentials 
			private $host = "localhost";
			private $db_name = "api_db";
			private $username = "cpsc471";
			private $password = "SoftwareEngineer";
			public $conn;
	
			//connect database
			public function getConnection(){
				$this->conn = null;
				try{
					$this->conn = mysqli($this->host, $this->username, $this->password, $this->db_name);
				}
				catch(mysqli_sql_exception $e){
					echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
				return $this->conn;
			}
	}
?>
	