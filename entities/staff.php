<?php
	class Staff{
		
		private $conn;
		private $table_name = "Staff";
		
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
		
		public function _construct($db){
			$this->conn = $db;
		}
		
	}
?>
			