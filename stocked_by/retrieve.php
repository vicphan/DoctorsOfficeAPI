<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/stocked_by.php"; //includes stocked_by.php file

$database = new Database();
$db = $database -> getConnection();

$stocked_by = new Stocked_by($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Pharm_name) and !empty($data->Med_name)){
	$stocked_by ->med_name = $data -> Med_name;
	$stocked_by ->pharm_name = $data -> Pharm_name;
	$stocked_by_entry = $stocked_by-> retrieve();
	$entries = $stocked_by_entry -> num_rows;
	if ($entries > 0){
		$row = $stocked_by_entry-> fetch_array();
		extract($row);
		$found_entry = array(	"Med_name" => $row['Med_name'],
							"Pharm_name" => $row['Pharm_name'],
							"Price" => $row['Price'],
						);
		http_response_code(200);
		echo json_encode($found_entry);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Stocked_by not found"));
	}
}
elseif (!empty($data->Pharm_name)){
	$stocked_by ->pharm_name = $data -> Pharm_name;
	$stocked_by_entry = $stocked_by-> retrieve_pharmacy();
	$entries = $stocked_by_entry -> num_rows;
	if ($entries > 0){
		$arr = array();
		while($row = $stocked_by_entry-> fetch_array()){
			extract($row);
			$found_entry = array(	"Med_name" => $row['Med_name'],
							"Pharm_name" => $row['Pharm_name'],
							"Price" => $row['Price'],
				);
			array_push($arr, $found_entry);
		}
		http_response_code(200);
		echo json_encode($arr);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Stocked_by not found"));
	}
}
elseif (!empty($data->Med_name)){
	$stocked_by ->med_name = $data -> Med_name;
	$stocked_by_entry = $stocked_by-> retrieve_medication();
	$entries = $stocked_by_entry -> num_rows;
	if ($entries > 0){
		$arr = array();
		while($row = $stocked_by_entry-> fetch_array()){
			extract($row);
			$found_entry = array(	"Med_name" => $row['Med_name'],
							"Pharm_name" => $row['Pharm_name'],
							"Price" => $row['Price'],
				);
			array_push($arr, $found_entry);
		}
		http_response_code(200);
		echo json_encode($arr);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Stocked_by not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid id"));
}

$db-> close();
?>