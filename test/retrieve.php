<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/test.php"; //includes test.php file

$database = new Database();
$db = $database -> getConnection();

$test = new Test($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->ID)){
	$test -> id = $data ->ID;
	$test_entry = $test-> retrieve();
	$entries = $test_entry -> num_rows;
	if ($entries > 0){
		$row = $test_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"ID" => $row['ID'],
							"Name" => $row['Name'],
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Test not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>