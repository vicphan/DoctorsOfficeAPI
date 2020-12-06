<?php
	class Equipment{
		
		private $conn;
		private $table_name = "equipment";
		
		//equipment attributes
		public $id;
		public $name;
		public $quantity;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllEquipment') ||
				!$this->conn->query('CREATE PROCEDURE selectAllEquipment () SELECT * FROM equipment')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS insertEquipment') ||
				!$this->conn->query('CREATE PROCEDURE insertEquipment (IN id INTEGER, name VARCHAR(45), quantity VARCHAR(45)) 
				INSERT INTO equipment VALUES (id, name, quantity)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS checkEquipment') ||
				!$this->conn->query('CREATE PROCEDURE checkEquipment (IN s_id INTEGER) SELECT * FROM equipment WHERE ID = s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeEquipment') ||
				!$this->conn->query('CREATE PROCEDURE removeEquipment (IN s_id INTEGER) DELETE FROM equipment WHERE ID = s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateNameEquipment') ||
				!$this->conn->query('CREATE PROCEDURE updateNameEquipment (IN name VARCHAR(45), s_id INTEGER) 
									 UPDATE equipment SET Name=name WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS updateQuantityEquipment') ||
				!$this->conn->query('CREATE PROCEDURE updateQuantityEquipment (IN quantity VARCHAR(45), s_id INTEGER) 
									 UPDATE equipment SET Quantity=quantity WHERE ID=s_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}

		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllEquipment()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL insertEquipment(?,?,?,?,?,?,?,?,?)");
			$statement -> bind_param("isissssss", $this->id, $this->name, $this->quantity);
			if ($statement -> execute()){
				return true;
			}
			
			return false;
		}
		
		
		public function check_user(){
			//checks if user is in database
			$check = $this->conn->prepare("CALL checkEquipment(?)");
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
				$statement = $this->conn->prepare("CALL removeEquipment(?)");
				$statement -> bind_param("i",  $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function retrieve(){
			$statement = $this->conn->prepare("CALL checkEquipment(?)");
			$statement -> bind_param("i", $this-> id);
			$statement -> execute();
			$result = $statement -> get_result();
			return $result;
		}
			
		
		public function update_name(){
			
			if ($this->check_user()){
				//updates name
				$statement = $this->conn->prepare("CALL updateNameEquipment(?,?)");
				$statement -> bind_param("si", $this->name, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
		
		public function update_quantity(){
			
			if ($this->check_user()){
				//updates quantity
				$statement = $this->conn->prepare("CALL updateQuantityEquipment(?,?)");
				$statement -> bind_param("si", $this->quantity, $this->id);
				if ($statement -> execute()){
					return true;
				}
			}
			
			return false;
		}
	}
?>
			