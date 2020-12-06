<?php

header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/staff.php"; //includes staff.php file

$database = new Database();
$db = $database -> getConnection();

$staff = new Staff($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data-> ID) && !empty($data-> Position) && !empty($data-> Salary) && !empty($data-> Fname) && !empty($data-> Lname) &&
    !empty($data-> Birth_day) && !empty($data-> Birth_month) && !empty($data-> Birth_year) && !empty($data-> Phone_number)){
		$staff -> id = $data-> ID;
		$staff -> position = $data -> Position;
		$staff -> salary = $data -> Salary;
		$staff -> fname = $data -> Fname;
		$staff -> lname = $data -> Lname;
		$staff -> birth_day = $data -> Birth_day;
		$staff -> birth_month = $data -> Birth_month;
		$staff -> birth_year = $data ->Birth_year;
		$staff -> phone_number = $data ->Phone_number;
		
		if ($staff -> add()){
			http_response_code(201);
			echo json_encode(array("message" => "staff was added"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff was not added"));
		}
	}
else{
	http_response_code(400);
	echo json_encode(array("message" => "data is incomplete"));
}

$db->close();
?>