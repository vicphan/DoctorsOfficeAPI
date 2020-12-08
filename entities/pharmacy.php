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
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllPharmacy') ||
				!$this->conn->query('CREATE PROCEDURE selectAllPharmacy () SELECT * FROM pharmacy')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertPharmacy') ||
				!$this->conn->query('CREATE PROCEDURE insertPharmacy (IN name VARCHAR(45), email VARCHAR(45), phone_number VARCHAR(45), address VARCHAR(45))
				INSERT INTO pharmacy VALUES (name, email, phone_number, address)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkPharmacy') ||
				!$this->conn->query('CREATE PROCEDURE checkPharmacy (IN p_name VARCHAR(45)) SELECT * FROM pharmacy WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removePharmacy') ||
				!$this->conn->query('CREATE PROCEDURE removePharmacy (IN p_name VARCHAR(45)) DELETE FROM pharmacy WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateEmailPharmacy') ||
				!$this->conn->query('CREATE PROCEDURE updateEmailPharmacy (IN email VARCHAR(45), p_name VARCHAR(45)) 
									 UPDATE pharmacy SET Email=email WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePhoneNumberPharmacy') ||
				!$this->conn->query('CREATE PROCEDURE updatePhoneNumberPharmacy (IN phone_number VARCHAR(45), p_name VARCHAR(45)) 
									 UPDATE pharmacy SET Phone_number=phone_number WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateAddressPharmacy') ||
				!$this->conn->query('CREATE PROCEDURE updateAddressPharmacy (IN address VARCHAR(45), p_name VARCHAR(45)) 
									 UPDATE pharmacy SET Address=address WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}

		}
	
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllPharmacy()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function insert(){
			$statement = $this->conn->prepare("CALL insertPharmacy(?,?,?,?)");
			$statement -> bind_param("ssss", $this->name, $this->email, $this->address, $this->phone_number);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_pharmacy(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkPharmacy(?)");
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
				$statement = $this->conn->prepare("CALL removePharmacy(?)");
				$statement -> bind_param("s",  $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkPharmacy(?)");
			$statement -> bind_param("s", $this-> name);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_email(){
			
			if ($this->check_pharmacy()){
				//updates email
				$statement = $this->conn->prepare("CALL updateEmailPharmacy(?,?)");
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
				$statement = $this->conn->prepare("CALL updatePhoneNumberPharmacy(?,?)");
				$statement -> bind_param("ss", $this->phone_number, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function updateAddressPharmacy(){
			
			if ($this->check_pharmacy()){
				//updates phone_number
				$statement = $this->conn->prepare("CALL updateAddressPharmacy(?,?)");
				$statement -> bind_param("ss", $this->address, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			