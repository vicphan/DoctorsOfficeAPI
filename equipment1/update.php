<?php
//updates info in database
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

if (!empty($data->ID)){
	$staff->id = $data->ID;
	//update position
	if (!empty($data-> Position)){
		$staff->position = $data->Position;
		if ($staff->update_position()){
			http_response_code(200);
			echo json_encode(array("message" => "staff position was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff position was unable to be modified"));
		}
	}
	//update salary
	if (!empty($data-> Salary)){
		$staff->salary = $data->Salary;
		if ($staff->update_salary()){
			http_response_code(200);
			echo json_encode(array("message" => "staff salary was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff salary was unable to be modified"));
		}
	}
	//update first name
	if (!empty($data-> Fname)){
		$staff->fname = $data->Fname;
		if ($staff->update_fname()){
			http_response_code(200);
			echo json_encode(array("message" => "staff first name was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff first name was unable to be modified"));
		}
	}
	//update last name
	if (!empty($data-> Lname)){
		$staff->lname = $data->Lname;
		if ($staff->update_lname()){
			http_response_code(200);
			echo json_encode(array("message" => "staff lname was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff lname was unable to be modified"));
		}
	}
	//update birth day
	if (!empty($data-> Birth_day) && !empty($data->Birth_month) && !empty($data->Birth_year)){
		$staff -> birth_day = $data -> Birth_day;
		$staff -> birth_month = $data -> Birth_month;
		$staff -> birth_year = $data ->Birth_year;
		if ($staff->update_birth()){
			http_response_code(200);
			echo json_encode(array("message" => "staff birthday was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff birthday was unable to be modified"));
		}
	}
	//update phone number
	if (!empty($data-> Phone_number)){
		$staff -> phone_number = $data ->Phone_number;
		if ($staff->update_pnum()){
			http_response_code(200);
			echo json_encode(array("message" => "staff phone number was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "staff phone number was unable to be modified"));
		}
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update staff info"));
}

$db->close();
?>
	
	