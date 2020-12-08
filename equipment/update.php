<?php
//updates info in database
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
	$equipment->id = $data->ID;
	//update name
	if (!empty($data-> Name)){
		$equipment->name = $data->Name;
		if ($equipment->update_name()){
			http_response_code(200);
			echo json_encode(array("message" => "equipment name was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "equipment name was unable to be modified"));
		}
	}
	//update quantity
	if (!empty($data-> Quantity)){
		$equipment -> quantity = $data ->Quantity;
		if ($equipment->update_quantity()){
			http_response_code(200);
			echo json_encode(array("message" => "equipment quantity was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "equipment quantity was unable to be modified"));
		}
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update equipment info"));
}

$db->close();
?>
	
	