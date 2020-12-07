<?php
	class Test{
		
		private $conn;
		private $table_name = "test";
		
		//test attributes
		public $id;
		public $name;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllTest') ||
				!$this->conn->query('CREATE PROCEDURE selectAllTest () SELECT * FROM test')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertTest') ||
				!$this->conn->query('CREATE PROCEDURE insertTest (IN id INTEGER, name VARCHAR(45)) INSERT INTO test VALUES (id, name)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkTest') ||
				!$this->conn->query('CREATE PROCEDURE checkTest (IN t_id INTEGER) SELECT * FROM test WHERE ID = t_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeTest') ||
				!$this->conn->query('CREATE PROCEDURE removeTest (IN t_id INTEGER) DELETE FROM test WHERE ID = t_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateNameTest') ||
				!$this->conn->query('CREATE PROCEDURE updateNameTest (IN name VARCHAR(45), t_id INTEGER) 
									 UPDATE test SET Name=name WHERE ID=t_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllTest()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertTest(?,?)");
			$statement -> bind_param("is", $this->id, $this->name);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		public function check_test(){
			//checks if test is in database
			$check = $this->conn->prepare("CALL checkTest(?)");
			$check -> bind_param("i", $this-> id);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}	
		public function remove(){
			
			if ($this->check_test()){
				//removes user if found in database
				$statement = $this->conn->prepare("CALL removeTest(?)");
				$statement -> bind_param("i",  $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkTest(?)");
			$statement -> bind_param("i", $this-> id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_name(){
			
			if ($this->check_test()){
				//updates name
				$statement = $this->conn->prepare("CALL updateNameTest(?,?)");
				$statement -> bind_param("si", $this->name, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}

	}
?>
			