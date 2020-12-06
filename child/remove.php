<?php
//removes entry from database
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
	if ($child -> removeParentChild()){
		http_response_code(200);
		echo json_encode(array("message" => "parent/child was successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "parent/child was unable to be removed"));
	}
}
elseif (!empty($data->Child_num)){
	$child -> child_num = $data -> Child_num;
	if ($child -> removeChild()){
		http_response_code(200);
		echo json_encode(array("message" => "child was successfully removed from parent"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "child was unable to be removed from parent"));
	}
}
elseif (!empty($data->Parent_num)){
	$child -> parent_num = $data -> Parent_num;
	if ($child -> removeParent()){
		http_response_code(200);
		echo json_encode(array("message" => "parent was successfully removed from child"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "parent was unable to be removed from child"));
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message" => "error"));
}

$db -> close();
?>