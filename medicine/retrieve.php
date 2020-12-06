<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/medicine.php"; //includes medicine.php file

$database = new Database();
$db = $database -> getConnection();

$medicine = new Medicine($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Name)){
	$medicine -> name = $data ->Name;
	$medicine_entry = $medicine-> retrieve();
	$entries = $medicine_entry -> num_rows;
	if ($entries > 0){
		$row = $medicine_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Name" => $row['Name'],
							"Brand" => $row['Brand']
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Medicine not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid health care number"));
}

$db-> close();
?>