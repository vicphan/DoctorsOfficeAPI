<?php
//removes entry from database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/equipment.php"; //includes equipment.php file

$database = new Database();
$db = $database -> getConnection();

$equipment = new Equipment($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->ID)){
	$equipment ->id = $data -> ID;
	if ($equipment -> remove()){
		http_response_code(200);
		echo json_encode(array("message" => "equipment successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "equipment was unable to be removed"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "error"));
}

$db -> close();
?>