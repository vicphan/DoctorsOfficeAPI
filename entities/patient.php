<?php 

	class Patient {
		
		private $conn;
		private $table_name = "patient";
		
		public $healthcare_num;
		public $address;
		public $email;
		public $birth_day;
		public $birth_month;
		public $birth_year;
		public $phone_number;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllPatients') ||
				!$this->conn->query('CREATE PROCEDURE selectAllPatients () SELECT * FROM patient')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertPatient') ||
				!$this->conn->query('CREATE PROCEDURE insertPatient (IN healthcareNum INTEGER, address VARCHAR(45), email VARCHAR(45), birth_day VARCHAR(45),
									 birth_month VARCHAR(45), birth_year VARCHAR(45), phone_number VARCHAR(45)) INSERT INTO patient VALUES (healthcareNum, address,
									 email, birth_day, birth_month, birth_year, phone_number)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkPatient') ||
				!$this->conn->query('CREATE PROCEDURE checkPatient (IN healthcareNum INTEGER) SELECT * FROM patient WHERE Healthcare_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removePatient') ||
				!$this->conn->query('CREATE PROCEDURE removePatient (IN healthcareNum INTEGER) DELETE FROM patient WHERE Healthcare_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateAddressPatient') ||
				!$this->conn->query('CREATE PROCEDURE updateAddressPatient (IN address VARCHAR(45), healthcareNum INTEGER) 
									 UPDATE patient SET Address=address WHERE Healthcare_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateEmailPatient') ||
				!$this->conn->query('CREATE PROCEDURE updateEmailPatient (IN email VARCHAR(45), healthcareNum INTEGER) 
									 UPDATE patient SET Email=email WHERE Healthcare_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateBirthdayPatient') ||
				!$this->conn->query('CREATE PROCEDURE updateBirthdayPatient ( IN birth_day VARCHAR(45),
									 birth_month VARCHAR(45), birth_year VARCHAR(45), healthcareNum INTEGER) 
									 UPDATE patient SET Birth_day=birth_day, Birth_month=birth_month, Birth_year=birth_year
									 WHERE Healthcare_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePhoneNumberPatient') ||
				!$this->conn->query('CREATE PROCEDURE updatePhoneNumberPatient (IN  phone_number VARCHAR(45), healthcareNum INTEGER) 
									 UPDATE patient SET Phone_number=phone_number WHERE Healthcare_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			
		}
		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllPatients()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertPatient(?,?,?,?,?,?,?)");
			$statement -> bind_param("issssss", $this->healthcare_num, $this->address, $this->email,  $this->birth_day,
									$this->birth_month,  $this->birth_year, $this->phone_number);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_user(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkPatient(?)");
			$check -> bind_param("i", $this-> healthcare_num);
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
				$statement = $this->conn->prepare("CALL removePatient(?)");
				$statement -> bind_param("i",  $this->healthcare_num);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkPatient(?)");
			$statement -> bind_param("i", $this-> healthcare_num);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_address(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("CALL updateAddressPatient(?,?)");
				$statement -> bind_param("si", $this->address, $this->healthcare_num);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_email(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("CALL updateEmailPatient(?,?)");
				$statement -> bind_param("si", $this->email, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_birth(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("CALL updateBirthdayPatient(?,?,?,?)");
				$statement -> bind_param("sssi", $this->birth_day, $this->birth_month, $this->birth_year, $this->healthcare_num);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_pnum(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("CALL updatePhoneNumberPatient(?,?)");
				$statement -> bind_param("si", $this->phone_number, $this->healthcare_num);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>