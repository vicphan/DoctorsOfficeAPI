<?php
//removes entry from database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/supplier.php"; //includes supplier.php file

$database = new Database();
$db = $database -> getConnection();

$supplier = new Supplier($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Name)){
	$supplier ->name = $data -> Name;
	if ($supplier -> remove()){
		http_response_code(200);
		echo json_encode(array("message" => "supplier successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "supplier was unable to be removed"));
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message" => "error"));
}

$db -> close();
?>