<?php
	/**
	 * Implements basic methods
	 * CREATE ORDER
	 * GET ORDER
	 * */

/**
 * Example of a form in order to pass an array
 * <form action="/myAction" method="post">
  *<input type="text" name="myList[1][ID]" value="1" />
*  <input type="text" name="myList[1][QTY]" value="3" />
*  <input type="text" name="myList[2][ID]" value="2" />
*  <input type="text" name="myList[2][QTY]" value="1" />
*  <input type="text" name="myList[3][ID]" value="3" />
*  <input type="text" name="myList[3][QTY]" value="2" />
*  <input type="submit" value="submit" />
*</form> */
class Order {
	function CreateOrder($cid , $ingarray, $price){

		$arrayIngredients=[];
		for ($i=0; $i < count($ingarray); $i++) {
			$tmp=explode(',',$ingarray[$i]);
			$quantity=$tmp[1];
			$name=substr($tmp[0],0,-1);
			$type=substr($tmp[0],-1);
			$arrayTmp=array($name,$type,$quantity);
			$arrayIngredients[$i]=$arrayTmp;
		}
		//file_put_contents('php://stderr',print_r(" ArrayIterator: ".$arrayIngredients[0][0]."\n\n\n\n\n\n" ,TRUE));

		$clientId = $cid;
		$ingredients = $ingarray;
		$totalPrice = $price;

		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "erick"; // add your password
		$dbname = 'foseto';

		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );

		// Check connection
		if (! $conn) {
			die ( "{error:'Connection failed:" . mysql_connect_error ())."'}";
		}

		//Insert the new order with the client name
		$query = "INSERT INTO orders (clientId) VALUES (".$clientId.")";

		if (! mysql_query ( $query, $conn )) {
			die ( "{'error':'Error description1: ".mysql_error($conn)."'}" );
		}

		//Get the ID.

		$query = "SELECT ID FROM orders WHERE clientId ='".$clientId."'ORDER BY id DESC";
		$result = mysql_query ( $query, $conn );
		if (! $result) {
			die ( "{'error':'Error description2: '".mysql_error($conn)."''}" );
		}
		$result = mysql_fetch_assoc($result);
		$id = $result['ID'];

		//Insert ingredients into order - ingredients relation table
		//This will prompt the trigger that calculates the total to be paid

		for ($j = 0 ; $j <= count($arrayIngredients); $j++){

			$querIngredient = "SELECT ID FROM ingredients WHERE type='".$arrayIngredients[$j][1]."' AND name='".$arrayIngredients[$j][0]."';";
			file_put_contents('php://stderr',print_r(" sadfasfds: ".$querIngredient."\n\n\n\n\n\n" ,TRUE));
			$resultIngredient = mysql_query ( $querIngredient, $conn );
			$resultIngredient = mysql_fetch_assoc($resultIngredient);
			$idIngredient = $resultIngredient['ID'];

			$query = "INSERT INTO order_ingredient (order_id, ingredient_id) VALUES ('".$id."',".$idIngredient.");";


file_put_contents('php://stderr',print_r(" ArrayIterator: ".$query."\n\n\n\n\n\n" ,TRUE));
			if (! mysql_query ( $query, $conn )) {
				die ( "{'error':'Error description3: ".mysql_error($conn)."'}" );
			}
		}

		//Get  ID from the order

		$query = "SELECT ID FROM orders WHERE ID = ".$id."";
		$result = mysql_query ( $query, $conn );

		if (! $result) {
			die ( "{'error':'Error description4: ".mysql_error($conn)."'}" );
		}

		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		echo $result;
	}

	/**
	 * Receives the ID and returns all the data from the order.
	 * */

	function getOrderData($pid){
		$id = $id;

		//Get all the data from the order

		$query = "SELECT * FROM orders WHERE ID = ".$id."";
		$result = mysql_query ( $query, $conn );

		if (! $result) {
			die ( "{'error':'Error description: ".mysql_error($conn)."'}" );
		}

		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		echo $result;
	}

	function editOrder($oid, $ingredients ){

		$orderId = $oid;
		$ingList = $ingredients;

		$query = "DELETE FROM order_ingredient WHERE order_id = " . $orderId .";";

		if (!mysql_query($query )){
			die ("{'error' : 'Error description:".mysql_error($conn)." '}");
		}

		for ($i = 0; $i <= count($ingredients); $i++){
			$query = "INSERT INTO order_ingredient (ingredient_id , quantity) VALUES (".$ingredients."[".$i."][ingid],". $ingredients."[".$i."][qty])";

			if (!mysql_query($query )){
				die ("{'error' : 'Error description:".mysql_error($conn)." '}");
			}
		}
	}


	function deleteOrder(){

	}


}


if (isset ($_REQUEST['co']) && isset($_POST['cid']) && isset($_POST['ing']) && isset($_POST['total'])){
	$order = new Order();
	$order->CreateOrder($_POST['cid'], $_POST['ing'], $_POST['total']);

}else if (isset($_REQUEST['god']) && isset($_REQUEST['id'])){

	$order = new Order();
	$order->getOrderData($_REQUEST['id']);

}else{
	die ( "{'error':'Check params.'}" );
}
?>
