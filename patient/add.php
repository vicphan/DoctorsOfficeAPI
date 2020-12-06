<?php

header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/patient.php"; //includes patient.php file

$database = new Database();
$db = $database -> getConnection();

$patient = new Patient($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data-> Healthcare_num) && !empty($data-> Address) && !empty($data-> Email) && 
    !empty($data-> Birth_day) && !empty($data-> Birth_month) && !empty($data-> Birth_year) && !empty($data-> Phone_number)){
		$patient -> healthcare_num = $data -> Healthcare_num;
		$patient -> address = $data -> Address;
		$patient -> email = $data -> Email;
		$patient -> birth_day = $data -> Birth_day;
		$patient -> birth_month = $data -> Birth_month;
		$patient -> birth_year = $data ->Birth_year;
		$patient -> phone_number = $data ->Phone_number;
		
		if ($patient -> add()){
			http_response_code(201);
			echo json_encode(array("message" => "patient was added"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "patient was not added"));
		}
	}
else{
	http_response_code(400);
	echo json_encode(array("message" => "patient is incomplete"));
}

$db->close();
?>