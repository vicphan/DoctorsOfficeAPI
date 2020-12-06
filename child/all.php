<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file
include_once "../entities/child.php"; //includes patient.php file

$database = new Database();
$db = $database -> getConnection();

$child = new Child($db);
$child_db = $child->all();
$child_arr = array();
$rows = $child_db -> num_rows;

if ($rows > 0){
	while ($row = $child_db->fetch_array()){
	extract($row);
	$child_entry = array(	"Parent_num" => $row["Parent_num"],
							"Child_num" => $row["Child_num"]
						);
	array_push($child_arr, $child_entry);
	}
	http_response_code(200);
	echo json_encode($child_arr);
}
else{
	http_response_code(404);
	echo json_encode(
		array("message" => "No records found")
	);
}
	
$db -> close();

?>