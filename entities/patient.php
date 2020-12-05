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
		}

		
		public function all(){
			$statement = $this->conn->prepare("SELECT * FROM ".$this->table_name);
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
	}
?>