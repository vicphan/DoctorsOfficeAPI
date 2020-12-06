<?php
	class Stocked_by{
		
		private $conn;
		private $table_name = "stocked_by";
		
		//stocked_by attributes
		public $med_name;
		public $pharm_name;
		public $price;
		
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
			$statement -> bind_param("ssi", $this->med_name, $this->pharm_name, $this->price);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		public function check_stocked_by(){
			//checks if user is in database
			$check = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE Med_name = ? AND Pharm_name = ?");
			$check -> bind_param("ss", $this-> med_name, $this-> pharm_name);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
		
				public function remove(){
			
			if ($this->check_stocked_by()){
				//removes user if found in database
				$statement = $this->conn->prepare("DELETE FROM ".$this->table_name." WHERE Med_name = ? AND Pharm_name = ?");
				$statement -> bind_param("iss", $this->price, $this->med_name, $this->pharm_name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE Med_name = ? AND Pharm_name = ?");
			$statement -> bind_param("iss", $this->price, $this->med_name, $this->pharm_name);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_price(){
			
			if ($this->check_stocked_by()){
				//updates price
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Position = ? WHERE Med_name = ? AND Pharm_name = ?");
				$statement -> bind_param("iss", $this->price, $this->med_name, $this->pharm_name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			