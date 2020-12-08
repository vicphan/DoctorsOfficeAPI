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
				!$this->conn->query('CREATE PROCEDURE checkPerforms (IN nurse INTEGER, test INTEGER) SELECT * FROM Performs WHERE Nurse_ID = nurse AND Test_ID = test')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removePerforms') ||
				!$this->conn->query('CREATE PROCEDURE removePerforms (IN nurse INTEGER, test INTEGER) DELETE FROM Performs WHERE Nurse_ID = nurse AND Test_ID = test')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkTestsDoneByNurse') ||
				!$this->conn->query('CREATE PROCEDURE checkTestsDoneByNurse (IN nurse INTEGER) SELECT * FROM Performs WHERE Nurse_ID = nurse')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkTests') ||
				!$this->conn->query('CREATE PROCEDURE checkTests (IN test INTEGER) SELECT * FROM Performs WHERE Test_ID = test')){
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
			$check -> bind_param("ii", $this->nurse_id, $this->test_id);
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
		
		public function retrieve_tests_by_nurse(){
			$statement = $this->conn->prepare("CALL checkTestsDoneByNurse(?)");
			$statement -> bind_param("i", $this->nurse_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
		
		public function retrieve_tests(){
			$statement = $this->conn->prepare("CALL checkTests(?)");
			$statement -> bind_param("i", $this->test_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
	}
?>
			