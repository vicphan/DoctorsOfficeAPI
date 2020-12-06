<?php
//updates info in database
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

if (!empty($data->Healthcare_num)){
	$patient->healthcare_num = $data->Healthcare_num;
	//update address
	if (!empty($data-> Address)){
		$patient->address = $data->Address;
		if ($patient->update_address()){
			http_response_code(200);
			echo json_encode(array("message" => "patient address was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "patient address was unable to be modified"));
		}
	}
	//update email
	if (!empty($data-> Email)){
		$patient ->email = $data ->Email;
		if ($patient->update_email()){
			http_response_code(200);
			echo json_encode(array("message" => "patient email was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "patient email was unable to be modified"));
		}
	}

	//update birth day
	if (!empty($data-> Birth_day) && !empty($data->Birth_month) && !empty($data->Birth_year)){
		$patient -> birth_day = $data -> Birth_day;
		$patient -> birth_month = $data -> Birth_month;
		$patient -> birth_year = $data ->Birth_year;
		if ($patient->update_birth()){
			http_response_code(200);
			echo json_encode(array("message" => "patient birthday was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "patient birthday was unable to be modified"));
		}
	}
	//update phone number
	if (!empty($data-> Phone_number)){
		$patient -> phone_number = $data ->Phone_number;
		if ($patient->update_pnum()){
			http_response_code(200);
			echo json_encode(array("message" => "patient phone number was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "patient phone number was unable to be modified"));
		}
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update patient info"));
}

$db->close();
?>
	
	