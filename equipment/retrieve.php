<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/equipment.php"; //includes equipment.php file

$database = new Database();
$db = $database -> getConnection();

$equipment = new Equipment($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->ID)){
	$equipment -> id = $data ->ID;
	$equipment_entry = $equipment-> retrieve();
	$entries = $equipment_entry -> num_rows;
	if ($entries > 0){
		$row = $equipment_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"ID" => $row['ID'],
							"Name" => $row['Name'],
							"Quantity" => $row['Quantity']
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Equipment not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>