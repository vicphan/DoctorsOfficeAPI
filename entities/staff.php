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
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllStaff') ||
				!$this->conn->query('CREATE PROCEDURE selectAllStaff () SELECT * FROM staff')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertStaff') ||
				!$this->conn->query('CREATE PROCEDURE insertStaff (IN id INTEGER, position VARCHAR(45), salary INTEGER, fname VARCHAR(45), lname VARCHAR(45),
				birth_day VARCHAR(45), birth_month VARCHAR(45), birth_year VARCHAR(45), phone_number VARCHAR(45)) INSERT INTO staff VALUES (id, position, salary, fname,
				lname, birth_day, birth_month, birth_year, phone_number)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkStaff') ||
				!$this->conn->query('CREATE PROCEDURE checkStaff (IN s_id INTEGER) SELECT * FROM staff WHERE ID = s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeStaff') ||
				!$this->conn->query('CREATE PROCEDURE removeStaff (IN s_id INTEGER) DELETE FROM staff WHERE ID = s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePositionStaff') ||
				!$this->conn->query('CREATE PROCEDURE updatePositionStaff (IN position VARCHAR(45), s_id INTEGER) 
									 UPDATE staff SET Position=position WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateSalaryStaff') ||
				!$this->conn->query('CREATE PROCEDURE updateSalaryStaff (IN salary INTEGER, s_id INTEGER) 
									 UPDATE staff SET Salary=salary WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateFnameStaff') ||
				!$this->conn->query('CREATE PROCEDURE updateFnameStaff (IN fname VARCHAR(45), s_id INTEGER) 
									 UPDATE staff SET Fname=fname WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateLnameStaff') ||
				!$this->conn->query('CREATE PROCEDURE updateLnameStaff (IN lname VARCHAR(45), s_id INTEGER) 
									 UPDATE staff SET Lname=lname WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateBirthdayStaff') ||
				!$this->conn->query('CREATE PROCEDURE updateBirthdayStaff ( IN birth_day VARCHAR(45),
									 birth_month VARCHAR(45), birth_year VARCHAR(45), s_id INTEGER) 
									 UPDATE staff SET Birth_day=birth_day, Birth_month=birth_month, Birth_year=birth_year
									 WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePhoneNumberStaff') ||
				!$this->conn->query('CREATE PROCEDURE updatePhoneNumberStaff (IN  phone_number VARCHAR(45), s_id INTEGER) 
									 UPDATE patient SET Phone_number=phone_number WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllStaff()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertStaff(?,?,?,?,?,?,?,?,?)");
			$statement -> bind_param("isissssss", $this->id, $this->position, $this->salary, $this->fname, $this->lname, $this->birth_day,
									$this->birth_month,  $this->birth_year, $this->phone_number);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_user(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkStaff(?)");
			$check -> bind_param("i", $this-> id);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
		
		public function remove(){
			
			if ($this->check_user()){
				//removes user if found in database
				$statement = $this->conn->prepare("CALL removeStaff(?)");
				$statement -> bind_param("i",  $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkStaff(?)");
			$statement -> bind_param("i", $this-> id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_position(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("CALL updatePositionStaff(?,?)");
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
				$statement = $this->conn->prepare("CALL updateSalaryStaff(?,?)");
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
				$statement = $this->conn->prepare("CALL updateFnameStaff(?,?)");
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
				$statement = $this->conn->prepare("CALL updateLnameStaff(?,?)");
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
				$statement = $this->conn->prepare("CALL updateBirthdayStaff(?,?,?,?)");
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
				$statement = $this->conn->prepare("CALL updatePhoneNumberStaff(?,?)");
				$statement -> bind_param("si", $this->phone_number, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			