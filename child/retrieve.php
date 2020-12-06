<?php
//file retrieves entry from database
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/child.php"; //includes child.php file

$database = new Database();
$db = $database -> getConnection();

$child = new Child($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Parent_num)){
	$child -> parent_num = $data -> Parent_num;
	$parent_entry = $child-> retrieveParent();
	$entries = $parent_entry -> num_rows;
	if ($entries > 0){
		$parent_array = array();
		while ($row = $parent_entry-> fetch_array()){
			extract($row);
			$found_entry = array(	"Parent_num" => $row["Parent_num"],
							"Child_num" => $row["Child_num"]
							);
			array_push($parent_array, $found_entry);
		}
		
		http_response_code(200);
		echo json_encode($parent_array);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Parent not found"));
	}
}
elseif (!empty($data->Child_num)){
	$child -> child_num = $data -> Child_num;
	$child_entry = $child-> retrieveChild();
	$entries = $child_entry -> num_rows;
	if ($entries > 0){
		$child_array = array();
		while ($row = $child_entry-> fetch_array()){
			extract($row);
			$found_entry = array(	"Parent_num" => $row["Parent_num"],
							"Child_num" => $row["Child_num"]
							);
			array_push($child_array, $found_entry);
		}
		
		http_response_code(200);
		echo json_encode($child_array);
	}
	else{
		http_response_code(404);
		echo json_encode(array("message"=>"Child not found"));
	}
}
else{
	http_response_code(503);
	echo json_encode(array("message"=>"Error. Please enter valid health care number"));
}

$db-> close();
?>