<?php
//file selects all entries in orders table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/orders.php"; //includes orders.php file

$database = new Database();
$db = $database -> getConnection();

$orders = new Orders($db);
$orders_db = $orders->all();
$orders_arr = array();
$rows = $orders_db -> num_rows;

if ($rows > 0){
	while ($row = $orders_db->fetch_array()){
	extract($row); 
	$orders_entry = array(	"Doc_ID" => $row['Doc_ID'],
							"Patient_num" => $row['Patient_num'],
							"Test_ID" => $row['Test_ID'],
						);
	array_push($orders_arr, $orders_entry);
	}

	http_response_code(200); //ok reponse
	echo json_encode($orders_arr);
}
else{
	http_response_code(404); //error response
	echo json_encode(
		array("message" => "No records found")
	);
}

$db -> close();

?>
	
