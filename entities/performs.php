<?php
	class Performs{
		
		private $conn;
		private $table_name = "performs";
		
		//performs attributes
		public $nurse_id;
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
			$statement = $this->conn->prepare("INSERT INTO ".$this->table_name." VALUES (?,?)");
			$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_performs(){
			//checks if user is in database
			$check = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE Nurse_ID = ? AND Test_ID = ?");
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
				$statement = $this->conn->prepare("DELETE FROM ".$this->table_name." WHERE Nurse_ID = ? AND Test_ID = ?");
				$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE Nurse_ID = ? AND Test_ID = ?");
			$statement -> bind_param("ii", $this->nurse_id, $this->test_id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
	}
?>
			