<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/visit.php"; //includes visit.php file

$database = new Database();
$db = $database -> getConnection();

$visit = new Visit($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Patient_num)){
	$visit -> patient = $data -> Patient_num;
	$visit_db = $visit->view();
	$visit_arr = array();
	$rows = $visit_db -> num_rows;

	if ($rows > 0){
		while ($row = $visit_db->fetch_array()){
		extract($row);
		$visit_entry = array(	"Patient_num" => $row["Patient_num"],
							"Date" => $row["Date"],
							"Diagnosis" => $row["Diagnosis"],
							"Treatment" => $row["Treatment"]
						);
		array_push($visit_arr, $visit_entry);
		}
		http_response_code(200);
		echo json_encode($visit_arr);
		}
	else{
		http_response_code(404);
		echo json_encode(
		array("message" => "No records found")
	);
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message" => "error"));
}

	
$db -> close();

?>