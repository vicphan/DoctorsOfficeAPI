<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/staff.php"; //includes staff.php file

$database = new Database();
$db = $database -> getConnection();

$staff = new Staff($db);
$staff_db = $staff->all();
$staff_arr = array();
$rows = $staff_db -> num_rows;

if ($rows > 0){
	while ($row = $staff_db->fetch_array()){
	extract($row);
	$staff_entry = array(	"ID" => $row['ID'],
							"Position" => $row['Position'],
							"Salary" => $row['Salary'],
							"Fname" => $row['Fname'],
							"Lname" => $row['Lname'],
							"Birth_day" => $row['Birth_day'],
							"Birth_month" => $row['Birth_month'],
							"Birth_year" => $row['Birth_year'],
							"Phone_number" => $row['Phone_number']
						);
	array_push($staff_arr, $staff_entry);
	}

	http_response_code(200);
	echo json_encode($staff_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
