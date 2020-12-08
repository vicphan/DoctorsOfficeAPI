<?php
	class Medicine{
		
		private $conn;
		private $table_name = "medicine";
		
		//medicine attributes
		public $name;
		public $brand;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllMedicine') ||
				!$this->conn->query('CREATE PROCEDURE selectAllMedicine () SELECT * FROM medicine')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertMedicine') ||
				!$this->conn->query('CREATE PROCEDURE insertMedicine (IN name VARCHAR(45), brand VARCHAR(45)) 
				INSERT INTO medicine VALUES (name, brand)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkMedicine') ||
				!$this->conn->query('CREATE PROCEDURE checkMedicine (IN p_name VARCHAR(45)) SELECT * FROM medicine WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeMedicine') ||
				!$this->conn->query('CREATE PROCEDURE removeMedicine (IN p_name VARCHAR(45)) DELETE FROM medicine WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateBrandMedicine') ||
				!$this->conn->query('CREATE PROCEDURE updateBrandMedicine (IN p_brand VARCHAR(45), p_name VARCHAR(45)) 
									 UPDATE medicine SET Brand=p_brand WHERE Name = p_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllMedicine()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertMedicine(?,?)");
			$statement -> bind_param("ss", $this->name, $this->brand);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_medicine(){
			//checks if medicine is in database
			$check = $this->conn->prepare("CALL checkMedicine(?)");
			$check -> bind_param("s", $this-> name);
			$check -> execute();
			$row = $check -> get_result()->num_rows;
			if ($row==1){
				return true;
			}
			return false;
		}
		
		public function remove(){
			
			if ($this->check_medicine()){
				//removes medicine
				$statement = $this->conn->prepare("CALL removeMedicine(?)");
				$statement -> bind_param("s",  $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkMedicine(?)");
			$statement -> bind_param("s", $this-> name);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
		
		public function update_brand(){
			if ($this->check_medicine()){
				//updates brand
				$statement = $this->conn->prepare("CALL updateBrandMedicine(?,?)");
				$statement -> bind_param("ss", $this->brand, $this->name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			