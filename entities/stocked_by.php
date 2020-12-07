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
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllStocked_by') ||
				!$this->conn->query('CREATE PROCEDURE selectAllStocked_by () SELECT * FROM Stocked_by')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertStocked_by') ||
				!$this->conn->query('CREATE PROCEDURE insertStocked_by (IN med_name VARCHAR(45), pharm_name VARCHAR(45), price INTEGER) 
				INSERT INTO Stocked_by VALUES (med_name, pharm_name, price)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkStocked_by') ||
				!$this->conn->query('CREATE PROCEDURE checkStocked_by (IN med_name VARCHAR(45), pharm_name VARCHAR(45)) SELECT * FROM Stocked_by WHERE Med_name = med_name AND Pharm_name = pharm_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeStocked_by') ||
				!$this->conn->query('CREATE PROCEDURE removeStocked_by (IN med_name VARCHAR(45), pharm_name VARCHAR(45)) DELETE FROM Stocked_by WHERE Med_name = med_name AND Pharm_name = pharm_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updatePriceStocked_by') ||
				!$this->conn->query('CREATE PROCEDURE updatePriceStaff (IN price INTEGER, med_name VARCHAR(45), pharm_name VARCHAR(45)) 
									 UPDATE stocked_by SET Price=price WHERE Med_name = med_name AND Pharm_name = pharm_name')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllStocked_by()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertStocked_by(?,?,?)");
			$statement -> bind_param("ssi", $this->med_name, $this->pharm_name, $this->price);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		public function check_stocked_by(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkStocked_by(?,?)");
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
				$statement = $this->conn->prepare("CALL removeStocked_by(?,?)");
				$statement -> bind_param("iss", $this->price, $this->med_name, $this->pharm_name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkStocked_by(?,?)");
			$statement -> bind_param("iss", $this->price, $this->med_name, $this->pharm_name);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_price(){
			
			if ($this->check_stocked_by()){
				//updates price
				$statement = $this->conn->prepare("CALL updatePriceStocked_by(?,?,?)");
				$statement -> bind_param("iss", $this->price, $this->med_name, $this->pharm_name);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			