<?php
//removes entry from database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database -> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS removeSupplies') ||
				!$db->query('CREATE PROCEDURE removeSupplies (IN  sup_name VARCHAR(45), equip_id INTEGER) 
				DELETE FROM prescribes WHERE Sup_name=sup_name AND Equip_id = equip_id')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Sup_id) && !empty($data->Equip_id)){
	$statement = $db -> prepare("CALL removeSupplies(?,?)");
	$statement -> bind_param("si", $data->Sup_id, $data->Equip_id);
	if ($statement->execute()){
		http_response_code(200);
		echo json_encode(array("message"=>"Supplies successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message"=>"Supplies not removed"));
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message"=>"error"));
}

$db->close();
?>