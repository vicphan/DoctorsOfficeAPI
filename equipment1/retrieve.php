<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/staff.php"; //includes staff.php file

$database = new Database();
$db = $database -> getConnection();

$staff = new Staff($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->ID)){
	$staff -> id = $data ->ID;
	$staff_entry = $staff-> retrieve();
	$entries = $staff_entry -> num_rows;
	if ($entries > 0){
		$row = $staff_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"ID" => $row['ID'],
							"Position" => $row['Position'],
							"Salary" => $row['Salary'],
							"Fname" => $row['Fname'],
							"Lname" => $row['Lname'],
							"Birth_day" => $row['Birth_day'],
							"Birth_month" => $row['Birth_month'],
							"Birth_year" => $row['Birth_year'],
							"Phone_number" => $row['Phone_number']
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Staff not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>