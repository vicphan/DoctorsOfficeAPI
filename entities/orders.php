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
				!$this->conn->query('CREATE PROCEDURE checkOrders (IN doctor INTEGER, patient INTEGER, test INTEGER) SELECT * FROM Orders WHERE Doc_ID = doctor AND Patient_num = patient AND Test_ID = test')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeOrders') ||
				!$this->conn->query('CREATE PROCEDURE removeOrders (IN doctor INTEGER, patient INTEGER, test INTEGER) DELETE FROM Orders WHERE Doc_ID = doctor AND Patient_num = patient AND Test_ID = test')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkOrdersDoctor') ||
				!$this->conn->query('CREATE PROCEDURE checkOrdersDoctor (IN doctor INTEGER) SELECT * FROM Orders WHERE Doc_ID = doctor')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkOrdersPatient') ||
				!$this->conn->query('CREATE PROCEDURE checkOrdersPatient (IN patient INTEGER) SELECT * FROM Orders WHERE Patient_num=patient')){
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
		
		public function retrieve_doctor_test(){
			$statement = $this->conn->prepare("CALL checkOrdersDoctor(?)");
			$statement -> bind_param("i", $this->doc_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
		
		public function retrieve_patient_test(){
			$statement = $this->conn->prepare("CALL checkOrdersPatient(?)");
			$statement -> bind_param("i", $this->patient_num);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}

	}
?>
			