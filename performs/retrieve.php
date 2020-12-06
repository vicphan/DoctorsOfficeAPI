<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/performs.php"; //includes performs.php file

$database = new Database();
$db = $database -> getConnection();

$performs = new Performs($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data-> Nurse_ID) && !empty($data-> Test_ID)){
	$performs -> nurse_id = $data-> Nurse_ID;
	$performs -> test_id = $data -> Test_ID;
	$performs_entry = $performs-> retrieve();
	$entries = $performs_entry -> num_rows;
	if ($entries > 0){
		$row = $performs_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Nurse_ID" => $row['Nurse_ID'],
							"Test_ID" => $row['Test_ID']
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Performs not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>