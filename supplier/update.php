<?php
//updates info in database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/supplier.php"; //includes supplier.php file

$database = new Database();
$db = $database -> getConnection();

$supplier = new Supplier($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Name)){
	$supplier->name = $data->Name;
	//update address
	if (!empty($data-> Address)){
		$supplier->address = $data->Address;
		if ($supplier->update_address()){
			http_response_code(200);
			echo json_encode(array("message" => "supplier address was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "supplier address was unable to be modified"));
		}
	}
	//update email
	if (!empty($data-> Email)){
		$supplier ->email = $data ->Email;
		if ($supplier->update_email()){
			http_response_code(200);
			echo json_encode(array("message" => "supplier email was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "supplier email was unable to be modified"));
		}
	}
	//update phone number
	if (!empty($data-> Phone_number)){
		$supplier -> phone_number = $data ->Phone_number;
		if ($supplier->update_pnum()){
			http_response_code(200);
			echo json_encode(array("message" => "supplier phone number was modified"));
		}
		else{
			http_response_code(503);
			echo json_encode(array("message" => "supplier phone number was unable to be modified"));
		}
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update supplier info"));
}

$db->close();
?>
	
	