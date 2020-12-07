<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/pharmacy.php"; //includes pharmacy.php file

$database = new Database();
$db = $database -> getConnection();

$pharmacy = new Pharmacy($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Name)){
	$pharmacy -> name = $data ->Name;
	$pharmacy_entry = $pharmacy-> retrieve();
	$entries = $pharmacy_entry -> num_rows;
	if ($entries > 0){
		$row = $pharmacy_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Name" => $row['Name'],
							"Email" => $row['Email'],
							"Address" => $row['Address'],
							"Phone_number" => $row['Phone_number'],
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Pharmacy not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>