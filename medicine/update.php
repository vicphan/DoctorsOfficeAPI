<?php
//updates info in database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/medicine.php"; //includes medicine.php file

$database = new Database();
$db = $database -> getConnection();

$medicine = new Medicine($db);

$data = json_decode(file_get_contents("php://input"));


if (!empty($data->Name) && !empty($data-> Brand)){
	$medicine ->name = $data ->Name;
	$medicine ->brand = $data ->Brand;
	if ($medicine->update_brand()){
		http_response_code(200);
		echo json_encode(array("message" => "medicine brand was modified"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "medicine brand was unable to be modified"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update medicine info"));
}

$db->close();
?>
	
	