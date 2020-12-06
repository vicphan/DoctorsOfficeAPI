<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/orders.php"; //includes orders.php file

$database = new Database();
$db = $database -> getConnection();

$orders = new Orders($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Doc_ID) AND !empty($data->Patient_num) AND !empty($data->Test_ID)){
	$orders ->doc_id = $data -> Doc_ID;
	$orders ->patient_num = $data -> Patient_num;
	$orders ->test_id = $data -> Test_ID;
	$orders_entry = $orders-> retrieve();
	$entries = $orders_entry -> num_rows;
	if ($entries > 0){
		$row = $orders_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Doc_ID" => $row['Doc_ID'],
							"Patient_num" => $row['Patient_num'],
							"Test_ID" => $row['Test_ID'],
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Orders not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>