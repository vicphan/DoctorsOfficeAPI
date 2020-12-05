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

if (!empty($data->ID)){
	$stocked_by->id = $data->ID;
	//update position
	if (!empty($data-> Position)){
		$stocked_by->position = $data->Position;
		if ($stocked_by->update_position()){
			http_response_code(200);
			echo json_encode(array("message" => "stocked_by position was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by position was unable to be modified"));
		}
	}
	//update salary
	if (!empty($data-> Salary)){
		$stocked_by->salary = $data->Salary;
		if ($stocked_by->update_salary()){
			http_response_code(200);
			echo json_encode(array("message" => "stocked_by salary was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by salary was unable to be modified"));
		}
	}
	//update first name
	if (!empty($data-> Fname)){
		$stocked_by->fname = $data->Fname;
		if ($stocked_by->update_fname()){
			http_response_code(200);
			echo json_encode(array("message" => "stocked_by first name was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by first name was unable to be modified"));
		}
	}
	//update last name
	if (!empty($data-> Lname)){
		$stocked_by->lname = $data->Lname;
		if ($stocked_by->update_lname()){
			http_response_code(200);
			echo json_encode(array("message" => "stocked_by lname was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by lname was unable to be modified"));
		}
	}
	//update birth day
	if (!empty($data-> Birth_day) && !empty($data->Birth_month) && !empty($data->Birth_year)){
		$stocked_by -> birth_day = $data -> Birth_day;
		$stocked_by -> birth_month = $data -> Birth_month;
		$stocked_by -> birth_year = $data ->Birth_year;
		if ($stocked_by->update_birth()){
			http_response_code(200);
			echo json_encode(array("message" => "stocked_by birthday was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by birthday was unable to be modified"));
		}
	}
	//update phone number
	if (!empty($data-> Phone_number)){
		$stocked_by -> phone_number = $data ->Phone_number;
		if ($stocked_by->update_pnum()){
			http_response_code(200);
			echo json_encode(array("message" => "stocked_by phone number was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "stocked_by phone number was unable to be modified"));
		}
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update stocked_by info"));
}

$db->close();
?>
	
	