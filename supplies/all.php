<?php
//file selects all entries in staff table from database 
header("Access-Control-Allow-Origin: *"); //anyone can read data
header("Content-Type: application/json; charset=UTF-8"); //returns json object

include_once "../config/database.php"; //includes database.php file

$database = new Database();
$db = $database -> getConnection();

if (!$db->query('DROP PROCEDURE IF EXISTS allSupplies') ||
				!$db->query('CREATE PROCEDURE allSupplies () 
				SELECT * FROM supplies')){
					echo json_encode(array("message"=>"Stored procedure creation failed: (". $db->errno .") ". $db->error));
				}
$statement = $db->prepare("CALL allSupplies()");
$statement -> execute();
$result = $statement -> get_result();
$arr = array();
$rows = $result -> num_rows;

if ($rows > 0){
	while ($row = $result->fetch_array()){
	extract($row);
	$entry = array(	"Sup_id" => $row["Sup_name"],
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
	
$db -> close();

?>