<?php
	class Orders{
		
		private $conn;
		private $table_name = "orders";
		
		//orders attributes
		public $doc_id;
		public $patient_num;
		public $test_id;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllOrders') ||
				!$this->conn->query('CREATE PROCEDURE selectAllOrders () SELECT * FROM Orders')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertOrders') ||
				!$this->conn->query('CREATE PROCEDURE insertOrders (IN doc_id INTEGER, patient_num INTEGER, test_id INTEGER) 
				INSERT INTO Orders VALUES (doc_id, patient_num, test_id)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkOrders') ||
				!$this->conn->query('CREATE PROCEDURE checkOrders (IN doc_id INTEGER, patient_num INTEGER, test_id INTEGER) SELECT * FROM Orders WHERE Doc_ID = doc_id AND Patient_num = patient_num AND Test_ID = test_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeOrders') ||
				!$this->conn->query('CREATE PROCEDURE removeOrders (IN doc_id INTEGER, patient_num INTEGER, test_id INTEGER) DELETE FROM Orders WHERE Doc_ID = doc_id AND Patient_num = patient_num AND Test_ID = test_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllOrders()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function insert(){
			$statement = $this->conn->prepare("CALL insertOrders(?,?,?)");
			$statement -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_orders(){
			//checks if orders is in database
			$check = $this->conn->prepare("CALL checkOrders(?,?,?)");
			$check -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
		
		public function remove(){
			
			if ($this->check_orders()){
				//removes orders if found in database
				$statement = $this->conn->prepare("CALL removeOrders(?,?,?)");
				$statement -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkOrders(?,?,?)");
			$statement -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}

	}
?>
			