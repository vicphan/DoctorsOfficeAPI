<?php

header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/stocked_by.php"; //includes stocked_by.php file

$database = new Database();
$db = $database -> getConnection();

$stocked_by = new Stocked_by($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data-> Med_name) && !empty($data-> Pharm_name) && !empty($data-> Price)){
		$stocked_by -> med_name = $data-> Med_name;
		$stocked_by -> pharm_name = $data -> Pharm_name;
		$stocked_by -> price = $data -> Price;
		
		if ($stocked_by -> add()){
			http_response_code(201);
			echo json_encode(array("message" => "stocked_by was added"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by was not added"));
		}
	}
else{
	http_response_code(400);
	echo json_encode(array("message" => "data is incomplete"));
}

$db->close();
?>