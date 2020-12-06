<?php
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database-> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS removeHelpStaff') ||
	!$db->query('CREATE PROCEDURE removeHelpStaff (IN doctorID INTEGER, nurseID INTEGER) DELETE FROM helps WHERE Doc_ID=doctorID AND Nurse_ID=nurseID')){
		echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
	}

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Doc_ID) && !empty($data->Nurse_ID)){
	$statement = $db->prepare("CALL removeHelpStaff(?,?)");
	$statement -> bind_param("ii", $data-> Doc_ID, $data-> Nurse_ID);
	if ($statement-> execute()){
		http_response_code(200);
		echo json_encode(array("message" => "doctor/nurse was successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message" => "doctor/nurse was not removed"));
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message" => "error"));
}
$db -> close();

?>
