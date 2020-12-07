<?php
//removes entry from database
header("Access-Control-Allow-OriginL *");//allows all users access
header("Content-Type: application/json; charset=UTF-8"); //sets content to json
header("Access-Control-Allow-Methods: POST"); //modify database
header("Access-Control-Max-Age: 300"); //results can be cached for 5 minutes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database -> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS removePrescription') ||
				!$db->query('CREATE PROCEDURE removePrescription (IN doctor_id INTEGER, patient_id INTEGER, medicine VARCHAR(45)) 
				DELETE FROM prescribes WHERE Doc_ID=doctor_id AND Patient_num=patient_id AND Med_name = medicine')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Doc_id) && !empty($data->Patient_num) && !empty($data->Med_name)){
	$statement = $db -> prepare("CALL removePrescription(?,?,?)");
	$statement -> bind_param("iis", $data->Doc_id, $data->Patient_num, $data-> Med_name);
	if ($statement->execute()){
		http_response_code(200);
		echo json_encode(array("message"=>"prescription successfully removed"));
	}
	else{
		http_response_code(503);
		echo json_encode(array("message"=>"prescription not removed"));
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message"=>"error"));
}

$db->close();
?>