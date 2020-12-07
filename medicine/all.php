<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/medicine.php"; //includes medicine.php file

$database = new Database();
$db = $database -> getConnection();

$medicine = new Medicine($db);
$medicine_db = $medicine->all();
$medicine_arr = array();
$rows = $medicine_db -> num_rows;

if ($rows > 0){
	while ($row = $medicine_db->fetch_array()){
	extract($row);
	$medicine_entry = array(	"Name" => $row['Name'],
							"Brand" => $row['Brand']
						);
	array_push($medicine_arr, $medicine_entry);
	}
	http_response_code(200);
	echo json_encode($medicine_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}
	




$db -> close();

?>
	
