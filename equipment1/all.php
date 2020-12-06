<?php
//file selects all entries in equipment table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/equipment.php"; //includes equipment.php file

$database = new Database();
$db = $database -> getConnection();

$equipment = new Equipment($db);
$equipment_db = $equipment->all();
$equipment_arr = array();
$rows = $equipment_db -> num_rows;

if ($rows > 0){
	while ($row = $equipment_db->fetch_array()){
	extract($row); 
	$equipment_entry = array(	"ID" => $row['ID'],
							"Name" => $row['Name'],
							"Quantity" => $row['Quantity']
						);
	array_push($equipment_arr, $equipment_entry);
	}

	http_response_code(200); //ok reponse
	echo json_encode($equipment_arr);
}
else{
	http_response_code(404); //error response
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
