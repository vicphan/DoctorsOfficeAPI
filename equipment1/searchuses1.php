<?php
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database -> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS searchHelpNurseStaff') ||
	!$db->query('CREATE PROCEDURE searchHelpNurseStaff (IN nurseID INTEGER) SELECT * FROM helps WHERE Nurse_ID=nurseID')){
		echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
	}

if (!$db->query('DROP PROCEDURE IF EXISTS searchHelpDoctorStaff') ||
	!$db->query('CREATE PROCEDURE searchHelpDoctorStaff (IN doctorID INTEGER) SELECT * FROM helps WHERE Doc_ID=doctorID')){
		echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
	}

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Doc_ID)){
	$statement = $db->prepare("CALL searchHelpDoctorStaff(?)");
	$statement -> bind_param("i", $data-> Doc_ID);
	$statement-> execute();
	$result = $statement->get_result();
	$doc_array = array();
	$count = $result->num_rows;
	if ($count>0){
		while($row= $result->fetch_array()){
			extract($row);
			$entry = array("Doc_ID" => $row["Doc_ID"],
						   "Nurse_ID" => $row["Nurse_ID"]);
			array_push($doc_array, $entry);
		}
		http_response_code(200);
		echo json_encode($doc_array);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Doctor not found"));
	}
}
elseif (!empty($data->Nurse_ID)){
	$statement = $db->prepare("CALL searchHelpNurseStaff(?)");
	$statement -> bind_param("i", $data-> Nurse_ID);
	$statement-> execute();
	$result = $statement->get_result();
	$nurse_array = array();
	$count = $result->num_rows;
	if ($count>0){
		while($row= $result->fetch_array()){
			extract($row);
			$entry = array("Doc_ID" => $row["Doc_ID"],
						   "Nurse_ID" => $row["Nurse_ID"]);
			array_push($nurse_array, $entry);
		}
		http_response_code(200);
		echo json_encode($nurse_array);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Nurse not found"));
	}
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}

$db->close();
?>
		
