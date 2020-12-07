<?php
//file selects all entries in performs table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/performs.php"; //includes performs.php file

$database = new Database();
$db = $database -> getConnection();

$performs = new Performs($db);
$performs_db = $performs->all();
$performs_arr = array();
$rows = $performs_db -> num_rows;

if ($rows > 0){
	while ($row = $performs_db->fetch_array()){
	extract($row); 
	$performs_entry = array(	"Nurse_ID" => $row['Nurse_ID'],
							"Test_ID" => $row['Test_ID']
						);
	array_push($performs_arr, $performs_entry);
	}

	http_response_code(200); //ok reponse
	echo json_encode($performs_arr);
}
else{
	http_response_code(404); //error response
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
