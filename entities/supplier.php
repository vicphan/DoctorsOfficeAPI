<?php
	class Supplier{
		
		private $conn;
		private $table_name = "supplier";
		
		//supplier attributes
		public $name;
		public $address;
		public $email;
		public $phone_number;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllSupplier') ||
				!$this->conn->query('CREATE PROCEDURE selectAllSupplier () SELECT * FROM supplier')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertSupplier') ||
				!$this->conn->query('CREATE PROCEDURE insertSupplier (IN name VARCHAR(45), address VARCHAR(45), email VARCHAR(45), phone_number VARCHAR(45))
				INSERT INTO supplier VALUES (name, address, email, phone_number)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkSupplier') ||
				!$this->conn->query('CREATE PROCEDURE checkSupplier (IN name VARCHAR(45)) SELECT * FROM supplier WHERE Name = name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeSupplier') ||
				!$this->conn->query('CREATE PROCEDURE removeSupplier (IN name VARCHAR(45)) DELETE FROM supplier WHERE Name = name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateAddressSupplier') ||
				!$this->conn->query('CREATE PROCEDURE updateAddressSupplier (IN address VARCHAR(45), name VARCHAR(45)) 
									 UPDATE supplier SET Address=address WHERE Name = name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateEmailSupplier') ||
				!$this->conn->query('CREATE PROCEDURE updateEmailSupplier (IN email VARCHAR(45), name VARCHAR(45)) 
									 UPDATE supplier SET Email=email WHERE Name=name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePhoneNumberSupplier') ||
				!$this->conn->query('CREATE PROCEDURE updatePhoneNumberSupplier (IN  phone_number VARCHAR(45), name VARCHAR(45)) 
									 UPDATE patient SET Phone_number=phone_number WHERE Name=name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllSupplier()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertSupplier(?,?,?,?)");
			$statement -> bind_param("ssss", $this->name, $this->address, $this->email, $this->phone_number);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_user(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkSupplier(?)");
			$check -> bind_param("s", $this-> name);
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
				$statement = $this->conn->prepare("CALL removeSupplier(?)");
				$statement -> bind_param("s",  $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkSupplier(?)");
			$statement -> bind_param("s", $this-> name);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_address(){
			
			if ($this->check_user()){
				//updates address
				$statement = $this->conn->prepare("CALL updateAddressSupplier(?,?)");
				$statement -> bind_param("ss", $this->address, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_email(){
			
			if ($this->check_user()){
				//updates email
				$statement = $this->conn->prepare("CALL updateEmailSupplier(?,?)");
				$statement -> bind_param("ss", $this->email, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_pnum(){
			
			if ($this->check_user()){
				//updates position
				$statement = $this->conn->prepare("CALL updatePhoneNumberSupplier(?,?)");
				$statement -> bind_param("ss", $this->phone_number, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			