<?php
include_once "patient.php"; //includes patient.php file

	class Child{
		
		private $conn;
		private $table_name = "has_child";
		
		public $parent_num;
		public $child_num;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllChildren') ||
				!$this->conn->query('CREATE PROCEDURE selectAllChildren () SELECT * FROM has_child')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeParent') ||
				!$this->conn->query('CREATE PROCEDURE removeParent (IN healthcareNum INTEGER) DELETE FROM has_child WHERE Parent_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeChild') ||
				!$this->conn->query('CREATE PROCEDURE removeChild (IN healthcareNum INTEGER) DELETE FROM has_child WHERE Child_num = healthcareNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeParentChild') ||
				!$this->conn->query('CREATE PROCEDURE removeParentChild (IN parentNum INTEGER, childNum INTEGER) DELETE FROM has_child WHERE Parent_num = parentNum AND
				Child_num=childNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS addParentChild') ||
				!$this->conn->query('CREATE PROCEDURE addParentChild (IN parentNum INTEGER, childNum INTEGER) INSERT INTO has_child VALUES (parentNum, childNum)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS getChild') ||
				!$this->conn->query('CREATE PROCEDURE getChild (IN childNum INTEGER) SELECT * FROM has_child WHERE Child_num=childNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS getParent') ||
				!$this->conn->query('CREATE PROCEDURE getParent (IN parentNum INTEGER) SELECT * FROM has_child WHERE Parent_num=parentNum')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}
		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllChildren()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function removeChild(){
			$statement = $this->conn->prepare("CALL removeChild(?)");
			$statement -> bind_param("i",  $this->child_num);
			if ($statement -> execute()){
					return true;
			}
			return false;
		}
		
		public function removeParent(){
			$statement = $this->conn->prepare("CALL removeParent(?)");
			$statement -> bind_param("i",  $this->parent_num);
			if ($statement -> execute()){
					return true;
			}
			return false;
		}
		
		public function removeParentChild(){
			$statement = $this->conn->prepare("CALL removeParentChild(?,?)");
			$statement -> bind_param("ii", $this->parent_num, $this->child_num);
			if ($statement -> execute()){
					return true;
			}
			return false;
		}
		
		public function retrieveChild(){
			$statement = $this->conn->prepare("CALL getChild(?)");
			$statement -> bind_param("i",  $this->child_num);
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function retrieveParent(){
			$statement = $this->conn->prepare("CALL getParent(?)");
			$statement -> bind_param("i",  $this->parent_num);
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function add(){
			$statement = $this->conn->prepare("CALL addParentChild(?,?)");
			$statement -> bind_param("ii", $this->parent_num, $this->child_num);
			if ($statement -> execute()){
					return true;
			}
			return false;
		}
		
	}
		
?>
			