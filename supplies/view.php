<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database -> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS viewSupplies') ||
				!$db->query('CREATE PROCEDURE viewSupplies (IN sup VARCHAR(45), equip INTEGER) 
				SELECT * FROM supplies WHERE Sup_name=sup AND Equip_id=equip')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
if (!$db->query('DROP PROCEDURE IF EXISTS viewSuppliesBySupplier') ||
				!$db->query('CREATE PROCEDURE viewSuppliesBySupplier (IN equip INTEGER) 
				SELECT * FROM supplies WHERE Equip_id=equip')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
if (!$db->query('DROP PROCEDURE IF EXISTS viewSupplierStock') ||
				!$db->query('CREATE PROCEDURE viewSupplierStock (IN sup VARCHAR(45)) 
				SELECT * FROM supplies WHERE Sup_name=sup')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->Sup_name) && !empty($data->Equip_id)){
	$statement = $db->prepare("CALL viewSupplies(?,?)");
	$statement -> bind_param("si", $data-> Sup_name, $data->Equip_id);
	$statement -> execute();
	$result = $statement -> get_result();
	$arr = array();
	$rows = $result -> num_rows;

	if ($rows > 0){		
		while ($row = $result->fetch_array()){
		extract($row);
		$entry = array(	"Sup_name" => $row["Sup_name"],
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
	$statement = $db->prepare("CALL viewSuppliesBySupplier(?)");
	$statement -> bind_param("i", $data->Equip_id);
	$statement -> execute();
	$result = $statement -> get_result();
	$arr = array();
	$rows = $result -> num_rows;

	if ($rows > 0){		
		while ($row = $result->fetch_array()){
		extract($row);
		$entry = array(	"Sup_name" => $row["Sup_name"],
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
elseif (!empty($data->Sup_name)){
	$statement = $db->prepare("CALL viewSupplierStock(?)");
	$statement -> bind_param("s", $data->Sup_name);
	$statement -> execute();
	$result = $statement -> get_result();
	$arr = array();
	$rows = $result -> num_rows;

	if ($rows > 0){		
		while ($row = $result->fetch_array()){
		extract($row);
		$entry = array(	"Sup_name" => $row["Sup_name"],
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
		echo json_encode(
			array("message" => "Error")
		);
}
	
$db -> close();

?>