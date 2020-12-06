<?php
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/visit.php"; //includes visit.php file

$database = new Database();
$db = $database-> getConnection();

$visit = new Visit($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Patient_num) && !empty($data->Date)){
	$visit -> patient = $data -> Patient_num;
	$visit -> date = $data -> Date;
	if ($visit-> bookAppointment()){
		http_response_code(200);
		echo json_encode(array("message" => "appointment successfully booked"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "appointment not booked"));
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message" => "error"));
}

$db -> close();
?>