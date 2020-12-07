<?php
//updates info in database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file
include_once "../entities/performs.php"; //includes performs.php file

$database = new Database();
$db = $database -> getConnection();

$performs = new Performs($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data-> Nurse_ID) && !empty($data-> Test_ID)){
		$performs -> nurse_id = $data-> Nurse_ID;
		$performs -> test_id = $data -> Test_ID;
}
else{
	http_response_code(503);
	echo json_encode(array("message" => "Unable to update performs info"));
}

$db->close();
?>