<?php
include_once "connect_sql.php";
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

		$arrayIngredients=array();
		for ($i=0; $i < count($ingarray); $i++) {
			$tmp=explode(',',$ingarray[$i]);
			$quantity=$tmp[1];
			$name=substr($tmp[0],0,-1);
			$type=substr($tmp[0],-1);
			$arrayTmp=array($name,$type,$quantity);
			$arrayIngredients[]=$arrayTmp;
		}
		//file_put_contents('php://stderr',print_r(" ArrayIterator: ".$arrayIngredients[0][0]."\n\n\n\n\n\n" ,TRUE));

		$clientId = $cid;
		$ingredients = $ingarray;
		$totalPrice = $price;

		//Insert the new order with the client name
		$query = "INSERT INTO orders (clientId,total) VALUES (".$clientId.",$totalPrice)";

		if (! mysql_query ( $query)) {
			die ( "{'error':'Error description1: ".mysql_error($conn)."'}" );
		}

		//Get the ID.

		$query = "SELECT ID FROM orders WHERE clientId ='".$clientId."'ORDER BY id DESC";
		$result = mysql_query ( $query);
		if (! $result) {
			die ( "{'error':'Error description2: '".mysql_error($conn)."''}" );
		}
		$result = mysql_fetch_assoc($result);
		$id = $result['ID'];

		file_put_contents('php://stderr',print_r(" ID: ".$id."\n\n\n\n\n\n" ,TRUE));
		//Insert ingredients into order - ingredients relation table
		//This will prompt the trigger that calculates the total to be paid

		for ($j = 0 ; $j < count($arrayIngredients); $j++){

			$query = "SELECT ID FROM ingredients WHERE type='".$arrayIngredients[$j][1]."' AND name='".$arrayIngredients[$j][0]."';";
			//file_put_contents('php://stderr',print_r(" sadfasfds: ".$query."\n\n\n\n\n\n" ,TRUE));
			$result = mysql_query ( $query);
			$result = mysql_fetch_assoc($result);
			$idIngredient = $result['ID'];

			$query = "INSERT INTO order_ingredient (order_id, ingredient_id, quantity) VALUES ('".$id."','".$idIngredient."','".$arrayIngredients[$j][2][0]."');";


			//file_put_contents('php://stderr',print_r(" ArrayIterator: ".$query."\n\n\n\n\n\n" ,TRUE));
			if (! mysql_query ( $query)) {
				die ( "{'error':'Error description3: ".mysql_error($conn)."'}" );
			}
		}

		//Get  ID from the order

		$query = "SELECT ID FROM orders WHERE ID = ".$id."";
		$result = mysql_query ( $query );

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
		$result = mysql_query ( $query);

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
