<?php
	class Visit{
		
		private $conn;
		private $table_name = 'visit';
		
		public $patient;
		public $date;
		public $diagnosis;
		public $treatment;
		public $new_date;
		
		public function __construct($db){
			$this->conn = $db;
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS bookVisit') ||
				!$this->conn->query('CREATE PROCEDURE bookVisit (IN num INTEGER, date_appointment VARCHAR(45)) INSERT INTO visit VALUES (num, date_appointment, null, null)')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS selectAllVisits') ||
				!$this->conn->query('CREATE PROCEDURE selectAllVisits () SELECT * FROM visit')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS viewAppointments') ||
				!$this->conn->query('CREATE PROCEDURE viewAppointments (IN num INTEGER) SELECT * FROM visit WHERE Patient_num=num')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS removeAppointment') ||
				!$this->conn->query('CREATE PROCEDURE removeAppointment (IN num INTEGER, date_appointment VARCHAR(45)) DELETE FROM visit WHERE Patient_num=num AND 
				Date=date_appointment')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
			if (!$this->conn->query('DROP PROCEDURE IF EXISTS addDiagnosisAndTreatment') ||
				!$this->conn->query('CREATE PROCEDURE addDiagnosisAndTreatment (IN num INTEGER, date_appointment VARCHAR(45), doc_diagnosis VARCHAR(45), doc_treatment VARCHAR(45)) 
				UPDATE visit SET Diagnosis=doc_diagnosis, Treatment=doc_treatment WHERE Patient_num=num AND Date=date_appointment')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $this->conn->errno .") ". $this->conn->error));
				}
		}
		
		public function bookAppointment(){
			$statement = $this->conn->prepare("CALL bookVisit(?,?)");
			$statement -> bind_param("is", $this->patient, $this->date);
			if ($statement->execute()){
				return true;
			}
			return false;
		}
		
		public function all(){
			$statement = $this->conn->prepare("CALL selectAllVisits()");
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		
		public function view(){
			$statement = $this->conn->prepare("CALL viewAppointments(?)");
			$statement -> bind_param("i", $this ->patient);
			$statement -> execute();
			$result = $statement->get_result();
			return $result;
		}
		public function remove(){
			$statement = $this->conn->prepare("CALL removeAppointment(?,?)");
			$statement -> bind_param("is", $this->patient, $this->date);
			if ($statement->execute()){
				return true;
			}
			return false;
		}
		public function change(){
			$statement = $this->conn->prepare("CALL removeAppointment(?,?)");
			$statement -> bind_param("is", $this->patient, $this->date);
			if ($statement->execute()){
				$statement2 = $this->conn->prepare("CALL bookVisit(?,?)");
				$statement2 -> bind_param("is", $this->patient, $this->new_date);
				if ($statement2 -> execute()){
					return true;
				}
				return false;
			}
			return false;
		}
		public function addDiagnosisAndTreatment(){
			$statement = $this->conn->prepare("CALL addDiagnosisAndTreatment(?,?,?,?)");
			$statement -> bind_param("isss", $this->patient, $this->date, $this->diagnosis, $this->treatment);
			if ($statement->execute()){
				return true;
			}
			return false;
		}
	}
		
?>
		
		