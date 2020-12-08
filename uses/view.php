<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database -> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS viewUses') ||
				!$db->query('CREATE PROCEDURE viewUses (IN doc INTEGER, equip INTEGER) 
				SELECT * FROM uses WHERE Doc_id=doc AND Equip_id=equip')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
				
if (!$db->query('DROP PROCEDURE IF EXISTS viewUsesByDoctor') ||
				!$db->query('CREATE PROCEDURE viewUsesByDoctor (IN doc INTEGER) 
				SELECT * FROM uses WHERE Doc_id=doc')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
				
if (!$db->query('DROP PROCEDURE IF EXISTS viewUsesByEquipment') ||
				!$db->query('CREATE PROCEDURE viewUsesByEquipment (IN equip INTEGER) 
				SELECT * FROM uses WHERE Equip_id=equip')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
				
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->Doc_id)&&!empty($data->Equip_id)){
	$statement = $db->prepare("CALL viewUses(?)");
	$statement -> bind_param("ii", $data-> Doc_id, $data->Equip_id);
	$statement -> execute();
	$result = $statement -> get_result();
	$arr = array();
	$rows = $result -> num_rows;

	if ($rows > 0){
		while ($row = $result->fetch_array()){
		extract($row);
		$entry = array(	"Doc_id" => $row["Doc_ID"],
					"Equip_id" => $row["Equip_ID"]
						);
		array_push($arr, $entry);
		}
		http_response_code(200);
		echo json_encode($arr);
	}
	else{
		http_response_code(404);
		echo json_encode(
			array("message" => "No records found")
		);
	}
}
elseif (!empty($data->Doc_id)){
	$statement = $db->prepare("CALL viewUsesByDoctor(?)");
	$statement -> bind_param("i", $data-> Doc_id);
	$statement -> execute();
	$result = $statement -> get_result();
	$arr = array();
	$rows = $result -> num_rows;

	if ($rows > 0){
		while ($row = $result->fetch_array()){
		extract($row);
		$entry = array(	"Doc_id" => $row["Doc_ID"],
					"Equip_id" => $row["Equip_ID"]
						);
		array_push($arr, $entry);
		}
		http_response_code(200);
		echo json_encode($arr);
	}
	else{
		http_response_code(404);
		echo json_encode(
			array("message" => "No records found")
		);
	}
}
elseif (!empty($data->Equip_id)){
	$statement = $db->prepare("CALL viewUsesByEquipment(?)");
	$statement -> bind_param("i", $data-> Equip_id);
	$statement -> execute();
	$result = $statement -> get_result();
	$arr = array();
	$rows = $result -> num_rows;

	if ($rows > 0){
		while ($row = $result->fetch_array()){
		extract($row);
		$entry = array(	"Doc_id" => $row["Doc_ID"],
					"Equip_id" => $row["Equip_ID"]
						);
		array_push($arr, $entry);
		}
		http_response_code(200);
		echo json_encode($arr);
	}
	else{
		http_response_code(404);
		echo json_encode(
			array("message" => "No records found")
		);
	}
}
else{
	http_response_code(400);
	echo json_encode(array("message"=>"error"));
}
	
$db -> close();

?>