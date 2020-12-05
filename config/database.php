<?php
	class Database{
			//database credentials 
			private $host = "localhost";
			private $db_name = "cpsc_471_project";
			private $username = "cpsc471";
			private $password = "SoftwareEngineer";
			public $conn;
	
			//connect database
			public function getConnection(){
				$this->conn = null;

				$this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
				
				if ($this->conn -> connect_errno){
					echo "Failed to connect to MySQL: " . $this->conn -> connect_error;
					exit();
				}
				
				return $this->conn;
			}
	}
?>
	