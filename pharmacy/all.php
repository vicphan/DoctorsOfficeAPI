<?php
//file selects all entries in pharmacy table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/pharmacy.php"; //includes pharmacy.php file

$database = new Database();
$db = $database -> getConnection();

$pharmacy = new Pharmacy($db);
$pharmacy_db = $pharmacy->all();
$pharmacy_arr = array();
$rows = $pharmacy_db -> num_rows;

if ($rows > 0){
	while ($row = $pharmacy_db->fetch_array()){
	extract($row); 
	$pharmacy_entry = array(	"Name" => $row['Name'],
							"Email" => $row['Email'],
							"Address" => $row['Address'],
							"Phone_number" => $row['Phone_number'],
						);
	array_push($pharmacy_arr, $pharmacy_entry);
	}

	http_response_code(200); //ok reponse
	echo json_encode($pharmacy_arr);
}
else{
	http_response_code(404); //error response
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
