<?php
	class Staff{
		
		private $conn;
		private $table_name = "staff";
		
		//staff attributes
		public $id;
		public $position;
		public $salary;
		public $fname;
		public $lname;
		public $birth_day;
		public $birth_month;
		public $birth_year;
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
			$statement = $this->conn->prepare("INSERT INTO ".$this->table_name." VALUES (?,?,?,?,?,?,?,?,?)");
			$statement -> bind_param("isissssss", $this->id, $this->position, $this->salary, $this->fname, $this->lname, $this->birth_day,
									$this->birth_month,  $this->birth_year, $this->phone_number);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		public function check_user(){
			//checks if user is in database
			$check = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE id = ?");
			$check -> bind_param("i", $this-> id);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
			
		
		public function update_position(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Position = ? WHERE ID = ?");
				$statement -> bind_param("si", $this->position, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_salary(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Salary = ? WHERE ID = ?");
				$statement -> bind_param("ii", $this->salary, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_fname(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Fname = ? WHERE ID = ?");
				$statement -> bind_param("si", $this->fname, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_lname(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Lname = ? WHERE ID = ?");
				$statement -> bind_param("si", $this->lname, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_birth(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Birth_day = ?, Birth_month = ?, Birth_year = ? WHERE ID = ?");
				$statement -> bind_param("sssi", $this->birth_day, $this->birth_month, $this->birth_year, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_pnum(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("UPDATE ".$this->table_name." SET Phone_number = ? WHERE ID = ?");
				$statement -> bind_param("si", $this->phone_number, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			