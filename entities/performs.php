<?php
	class Performs{
		
		private $conn;
		private $table_name = "performs";
		
		//performs attributes
		public $nurse_id;
		public $test_id;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllPerforms') ||
				!$this->conn->query('CREATE PROCEDURE selectAllPerforms () SELECT * FROM Performs')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertPerforms') ||
				!$this->conn->query('CREATE PROCEDURE insertPerforms (IN nurse_id INTEGER, test_id INTEGER) 
				INSERT INTO Performs VALUES (nurse_id, test_id)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkPerforms') ||
				!$this->conn->query('CREATE PROCEDURE checkPerforms (IN nurse_id INTEGER, test_id INTEGER) SELECT * FROM Performs WHERE Nurse_ID = nurse_id AND Test_ID = test_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removePerforms') ||
				!$this->conn->query('CREATE PROCEDURE removePerforms (IN nurse_id INTEGER, test_id INTEGER) DELETE FROM Performs WHERE Nurse_ID = nurse_id AND Test_ID = test_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllPerforms()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertPerforms(?,?)");
			$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_performs(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkPerforms(?,?)");
			$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
		
		public function remove(){
			
			if ($this->check_performs()){
				//removes user if found in database
				$statement = $this->conn->prepare("CALL removePerforms(?,?)");
				$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkPerforms(?,?)");
			$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
	}
?>
			