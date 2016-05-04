<?php
	/**
	 * Implements basic methods
	 * CREATE ORDER
	 * GET ORDER
	 * */
include 'connect_sql.php';
/**
 * Example of a form in order to pass an array
 * <form action="/myAction" method="post">
  <input type="text" name="myList[1][ID]" value="1" />
  <input type="text" name="myList[1][QTY]" value="3" />
  <input type="text" name="myList[2][ID]" value="2" />
  <input type="text" name="myList[2][QTY]" value="1" />
  <input type="text" name="myList[3][ID]" value="3" />
  <input type="text" name="myList[3][QTY]" value="2" />
  <input type="submit" value="submit" />
</form>*/
class Order {
	function CreateOrder($cname , $ingarray){
		$clientName = $cname;
		$ingredients = $ingarray;

		//Insert the new order with the client name
		$query = "INSERT INTO orders (client) VALUES (".$clientName.")";

		if (! mysql_query ( $query, $conn )) {
			die ( "{'error':'Error description: ".mysql_error($conn)."'}" );
		}

		//Get the ID.

		$query = "SELECT ID FROM orders WHERE cient =".$clientName."";
		$result = mysql_query ( $query, $conn );
		if (! $result) {
			die ( "{'error':'Error description: ".mysql_error($conn)."'}" );
		}
		$id = $result ["id"];

		//Insert ingredients into order - ingredients relation table
		//This will prompt the trigger that calculates the total to be paid
		for ($i = 0 ; i <= $ingredients; $i++){

			$query = "INSERT INTO order_ingredient (order_id, ingredient_id,quantity) VALUES (".$id.",".$ingredients[$i][$idi].",".$ingredients[$i]["qty"].");";
			if (! mysql_query ( $query, $conn )) {
				die ( "{'error':'Error description: ".mysql_error($conn)."'}" );
			}
		}

		//Get  ID from the order

		$query = "SELECT ID FROM orders WHERE ID = ".$id."";
		$result = mysql_query ( $query, $conn );

		if (! $result) {
			die ( "{'error':'Error description: ".mysql_error($conn)."'}" );
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


if (isset ($_REQUEST['co']) &&isset($_REQUEST['cname']) && isset($_POST['ing[]']) ){

	$order = new Order();
	$order->CreateOrder($_POST['cname'], $_POST['ing[]']);

}else if (isset($_REQUEST['god']) && isset($_REQUEST['id'])){

	$order = new Order();
	$order->getOrderData($_REQUEST['id']);

}else{
	die ( "{'error':'Check params.'}" );
}
?>
