<?php

class Ingredient {
	function checkType($type) {
		$query = "SELECT * FROM ingredients WHERE type =" . $type . ";";
		$result = mysql_query ( $query );
		
		if (! $result) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		
		$result = json_encode ( $result );
		echo $result;
	}
}

if (isset($_REQUEST["ct"]) && isset($_POST["type"])){
	$ing = new Ingredient();
	$ing->checkType($_POST["type"]);
}

?>