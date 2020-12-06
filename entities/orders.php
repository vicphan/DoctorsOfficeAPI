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
		}

		
		public function all(){
			$statement = $this->conn->prepare("SELECT * FROM ".$this->table_name);
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("INSERT INTO ".$this->table_name." VALUES (?,?,?)");
			$statement -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_orders(){
			//checks if orders is in database
			$check = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE doc_id = ? AND patient_num = ? AND test_id = ?");
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
				$statement = $this->conn->prepare("DELETE FROM ".$this->table_name." WHERE doc_id = ? AND patient_num = ? AND test_id = ?");
				$statement -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE doc_id = ? AND patient_num = ? AND test_id = ?");
			$statement -> bind_param("iii", $this->doc_id, $this->patient_num, $this->test_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}

	}
?>
			