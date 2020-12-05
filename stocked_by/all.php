<?php
//file selects all entries in stocked_by table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/stocked_by.php"; //includes stocked_by.php file

$database = new Database();
$db = $database -> getConnection();

$stocked_by = new Stocked_by($db);
$stocked_by_db = $stocked_by->all();
$stocked_by_arr = array();
$rows = $stocked_by_db -> num_rows;

if ($rows > 0){
	while ($row = $stocked_by_db->fetch_array()){
	extract($row);
	$stocked_by_entry = array(	"Med_name" => $row['Med_name'],
							"Pharm_name" => $row['Pharm_name'],
							"Price" => $row['Price'],
						);
	array_push($stocked_by_arr, $stocked_by_entry);
	}

	http_response_code(200);
	echo json_encode($stocked_by_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
