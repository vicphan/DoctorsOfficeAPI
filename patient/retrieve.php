<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/patient.php"; //includes patient.php file

$database = new Database();
$db = $database -> getConnection();

$patient = new Patient($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Healthcare_num)){
	$patient -> healthcare_num = $data ->Healthcare_num;
	$patient_entry = $patient-> retrieve();
	$entries = $patient_entry -> num_rows;
	if ($entries > 0){
		$row = $patient_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Healthcare_num" => $row['Healthcare_num'],
							"Fname" => $row['Fname'],
							"Lname" => $row['Lname'],
							"Address" => $row['Address'],
							"Email" => $row['Email'],
							"Birth_day" => $row['Birth_day'],
							"Birth_month" => $row['Birth_month'],
							"Birth_year" => $row['Birth_year'],
							"Phone_number" => $row['Phone_number']
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Patient not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid health care number"));
}

$db-> close();
?>