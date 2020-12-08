<?php
//updates info in database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/pharmacy.php"; //includes pharmacy.php file

$database = new Database();
$db = $database -> getConnection();

$pharmacy = new Pharmacy($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Name)){
	$pharmacy->name = $data->Name;
	//update email
	if (!empty($data-> Email)){
		$pharmacy->email = $data->Email;
		if ($pharmacy->update_email()){
			http_response_code(200);
			echo json_encode(array("message" => "pharmacy email was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "pharmacy email was unable to be modified"));
		}
	}
	//update address
	if (!empty($data-> Address)){
		$pharmacy->address = $data->Address;
		if ($pharmacy->updateAddressPharmacy()){
			http_response_code(200);
			echo json_encode(array("message" => "pharmacy address was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "pharmacy address was unable to be modified"));
		}
	}
	//update phone number
	if (!empty($data-> Phone_number)){
		$pharmacy->phone_number = $data->Phone_number;
		if ($pharmacy->update_phone_number()){
			http_response_code(200);
			echo json_encode(array("message" => "pharmacy phone number was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "pharmacy phone number was unable to be modified"));
		}
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update pharmacy info"));
}

$db->close();
?>
	
	