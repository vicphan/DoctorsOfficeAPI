<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/patient.php"; //includes patient.php file

$database = new Database();
$db = $database -> getConnection();

$patient = new Patient($db);
$patient_db = $patient->all();
$patient_arr = array();
$rows = $patient_db -> num_rows;

if ($rows > 0){
	while ($row = $patient_db->fetch_array()){
	extract($row);
	$patient_entry = array(	"Healthcare_num" => $row['Healthcare_num'],
							"Address" => $row['Address'],
							"Email" => $row['Email'],
							"Birth_day" => $row['Birth_day'],
							"Birth_month" => $row['Birth_month'],
							"Birth_year" => $row['Birth_year'],
							"Phone_number" => $row['Phone_number']
						);
	array_push($patient_arr, $patient_entry);
	}
	http_response_code(200);
	echo json_encode($patient_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}
	




$db -> close();

?>
	
