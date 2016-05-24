<?php
//include 'connect_sql.php';
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
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "1807"; // add your password
		$dbname = 'foseto';


		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );

		$query = "DELETE FROM ingredients WHERE id=".$ingid.";";

		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}

		header('location:../project/nuevoingrediente.html');
	}

	function insertIngredient($type,$name,$price,$link){
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "1807"; // add your password
		$dbname = 'foseto';


		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );


		$query = "INSERT INTO ingredients (type,name,available,price,image) VALUES (".$type.",'".$name."',1,".$price.",'".$link."');";
		file_put_contents('php://stderr',print_r(" final: ".$query."\n\n\n\n\n\n" ,TRUE));
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		header('location:../project/nuevoingrediente.html');
	}

	function editIngredient($ingid,$name,$price,$link,$available) {
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "1807"; // add your password
		$dbname = 'foseto';


		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );


		$query = "UPDATE ingredients SET name='".$name."', price=".$price.", image ='".$link."',available=".$available." WHERE id=".$ingid.";";
		file_put_contents('php://stderr',print_r(" final: ".$query."\n\n\n\n\n\n" ,TRUE));
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		header('location:../project/nuevoingrediente.html');
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
}else if(isset($_REQUEST["ii"]) && isset($_POST["type"]) && isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["link"])){
	$ing = new Ingredient();
	$ing->insertIngredient($_POST["type"],$_POST["name"],$_POST["price"],$_POST["link"]);
}else if (isset($_REQUEST["sai"])){
	$ing = new Ingredient();
	$ing->showAvailableIngredients();
}else if (isset($_REQUEST["ei"]) && isset($_POST["ingid"]) && isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["link"]) && isset($_POST["available"])){
	$ing = new Ingredient();
	$ing->editIngredient($_POST["ingid"],$_POST["name"],$_POST["price"],$_POST["link"],$_POST["available"]);
}else if (isset($_REQUEST["di"]) && isset($_POST["ingid"])){
	$ing = new Ingredient();
	$ing->deleteIngredient($_POST["ingid"]);
}
?>
