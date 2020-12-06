<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/supplier.php"; //includes supplier.php file

$database = new Database();
$db = $database -> getConnection();

$supplier = new Supplier($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Name)){
	$supplier -> name = $data ->Name;
	$supplier_entry = $supplier-> retrieve();
	$entries = $supplier_entry -> num_rows;
	if ($entries > 0){
		$row = $supplier_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Name" => $row['Name'],
							"Address" => $row['Address'],
							"Email" => $row['Email'],
							"Phone_number" => $row['Phone_number']
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Supplier not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid health care number"));
}

$db-> close();
?>