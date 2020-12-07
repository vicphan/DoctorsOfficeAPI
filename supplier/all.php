<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/supplier.php"; //includes supplier.php file

$database = new Database();
$db = $database -> getConnection();

$supplier = new Supplier($db);
$supplier_db = $supplier->all();
$supplier_arr = array();
$rows = $supplier_db -> num_rows;

if ($rows > 0){
	while ($row = $supplier_db->fetch_array()){
	extract($row);
	$supplier_entry = array(	"Name" => $row['Name'],
							"Address" => $row['Address'],
							"Email" => $row['Email'],
							"Phone_number" => $row['Phone_number']
						);
	array_push($supplier_arr, $supplier_entry);
	}
	http_response_code(200);
	echo json_encode($supplier_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}
	




$db -> close();

?>
	
