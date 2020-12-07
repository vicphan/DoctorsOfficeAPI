<?php
//removes entry from database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
	if ($orders -> remove()){
		http_response_code(200);
		echo json_encode(array("message" => "orders successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "orders was unable to be removed"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "error"));
}

$db -> close();
?>