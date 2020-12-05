<?php
	class Test{
		
		private $conn;
		private $table_name = "test";
		
		//test attributes
		public $id;
		public $name;
		
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
			$statement -> bind_param("is", $this->id, $this->name);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		public function check_test(){
			//checks if test is in database
			$check = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE id = ?");
			$check -> bind_param("i", $this-> id);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
			
		
		public function update_name(){
			
			if ($this->check_test()){
				//updates name
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET name = ? WHERE ID = ?");
				$statement -> bind_param("si", $this->name, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}

	}
?>
			