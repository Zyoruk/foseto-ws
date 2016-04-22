<?php
include 'connect_sql.php';
class Ingredient {
	function checkType($type) {
		$query = "SELECT * FROM ingredients WHERE type =" . $type . ";";
		$result = mysql_query ( $query );
		
		if (! $result) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		$result = mysql_fetch_assoc($result);
		$result = json_encode ( $result );
		echo $result;
	}
	
	function deleteIngredient($ingid) {
		$query = "DELETE FROM ingredients WHERE id = " . $ingid . "";
		
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
	}
	
	function editIngredient($ingid, $array) {
		$query = "UPDATE ingredients SET";
		$first = TRUE;
		foreach ( $array as $key => $value ) {
			if ($first) {
				$query = $query . $key . "=" . $value;
				$first = FALSE;
			} else {
				$query = $query . "," . $key . "=" . $value;
			}
		}
		$query = $query . "WHERE id = " .$ingid . ";";
		
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
	}
	
	function showAvailableIngredients (){
		$query = "SELECT id, name,status FROM ingredients WHERE status = N OR status = F; ";
		$result = mysql_query ( $query );
		
		if (! $result) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		$result = mysql_fetch_assoc($result);
		$result = json_encode ( $result );
	}
}

if (isset($_REQUEST["ct"]) && isset($_POST["type"])){
	$ing = new Ingredient();
	$ing->checkType($_POST["type"]);
}

?>