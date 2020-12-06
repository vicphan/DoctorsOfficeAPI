<?php
	class Pharmacy{
		
		private $conn;
		private $table_name = "pharmacy";
		
		//pharmacy attributes
		public $name;
		public $email;
		public $address;
		public $phone_number;
		
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
			$statement = $this->conn->prepare("INSERT INTO ".$this->table_name." VALUES (?,?,?,?)");
			$statement -> bind_param("ssss", $this->name, $this->email, $this->address, $this->phone_number);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_user(){
			//checks if user is in database
			$check = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE Name = ?");
			$check -> bind_param("s", $this-> name);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
		
		public function remove(){
			
			if ($this->check_pharmacy()){
				//removes user if found in database
				$statement = $this->conn->prepare("DELETE FROM ".$this->table_name." WHERE Name = ?");
				$statement -> bind_param("s",  $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE Name = ?");
			$statement -> bind_param("s", $this-> name);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_email(){
			
			if ($this->check_pharmacy()){
				//updates email
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Email = ? WHERE Name = ?");
				$statement -> bind_param("ss", $this->email, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_phone_number(){
			
			if ($this->check_pharmacy()){
				//updates address
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Address = ? WHERE Name = ?");
				$statement -> bind_param("ss", $this->address, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_address(){
			
			if ($this->check_user()){
				//updates phone_number
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Phone_number = ? WHERE Name = ?");
				$statement -> bind_param("ss", $this->phone_number, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			