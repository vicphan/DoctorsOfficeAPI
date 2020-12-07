<?php
//file selects all entries in test table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/test.php"; //includes test.php file

$database = new Database();
$db = $database -> getConnection();

$test = new Test($db);
$test_db = $test->all();
$test_arr = array();
$rows = $test_db -> num_rows;

if ($rows > 0){
	while ($row = $test_db->fetch_array()){
	extract($row);
	$test_entry = array(	"ID" => $row['ID'],
							"Name" => $row['Name'],
						);
	array_push($test_arr, $test_entry);
	}

	http_response_code(200);
	echo json_encode($test_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
