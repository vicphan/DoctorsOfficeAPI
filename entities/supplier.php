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
				!$this->conn->query('CREATE PROCEDURE checkSupplier (IN s_name VARCHAR(45)) SELECT * FROM supplier WHERE Name = s_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeSupplier') ||
				!$this->conn->query('CREATE PROCEDURE removeSupplier (IN s_name VARCHAR(45)) DELETE FROM supplier WHERE Name = s_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateAddressSupplier') ||
				!$this->conn->query('CREATE PROCEDURE updateAddressSupplier (IN s_address VARCHAR(45), s_name VARCHAR(45)) 
									 UPDATE supplier SET Address=s_address WHERE Name = s_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateEmailSupplier') ||
				!$this->conn->query('CREATE PROCEDURE updateEmailSupplier (IN s_email VARCHAR(45), s_name VARCHAR(45)) 
									 UPDATE supplier SET Email=s_email WHERE Name=s_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePhoneNumberSupplier') ||
				!$this->conn->query('CREATE PROCEDURE updatePhoneNumberSupplier (IN  s_phone_number VARCHAR(45), s_name VARCHAR(45)) 
									 UPDATE supplier SET Phone_number=s_phone_number WHERE Name=s_name')){
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
		
		
		public function check_supplier(){
			//checks if supplier is in database
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
			
			if ($this->check_supplier()){
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
			
			if ($this->check_supplier()){
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
			
			if ($this->check_supplier()){
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
			
			if ($this->check_supplier()){
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
			