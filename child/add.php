<?php

header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/child.php"; //includes child.php file

$database = new Database();
$db = $database -> getConnection();

$child = new Child($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Child_num) && !empty($data->Parent_num)){
		$child -> child_num = $data -> Child_num;
		$child -> parent_num = $data -> Parent_num;
		
		if ($child -> add()){
			http_response_code(201);
			echo json_encode(array("message" => "parent and child were added"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "parent and child was not added"));
		}
	}
else{
	http_response_code(400);
	echo json_encode(array("message" => "parent and child are incomplete"));
}

$db->close();
?>