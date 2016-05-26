<?php
include_once 'connect_sql.php';
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

		if (! mysql_query ( $query )) {
			die ( "{'error':'Error description1: ".mysql_error()."'}" );
		}

		//Get the ID.

		$query = "SELECT ID FROM orders WHERE clientId ='".$clientId."'ORDER BY id DESC";
		$result = mysql_query ( $query );
		if (! $result) {
			die ( "{'error':'Error description2: '".mysql_error()."''}" );
		}
		$result = mysql_fetch_assoc($result);
		$id = $result['ID'];

		file_put_contents('php://stderr',print_r(" ID: ".$id."\n\n\n\n\n\n" ,TRUE));
		//Insert ingredients into order - ingredients relation table
		//This will prompt the trigger that calculates the total to be paid

		for ($j = 0 ; $j < count($arrayIngredients); $j++){

			$query = "SELECT ID FROM ingredients WHERE type='".$arrayIngredients[$j][1]."' AND name='".$arrayIngredients[$j][0]."';";
			//file_put_contents('php://stderr',print_r(" sadfasfds: ".$query."\n\n\n\n\n\n" ,TRUE));
			$result = mysql_query ( $query );
			$result = mysql_fetch_assoc($result);
			$idIngredient = $result['ID'];

			$query = "INSERT INTO order_ingredient (order_id, ingredient_id, quantity) VALUES ('".$id."','".$idIngredient."','".$arrayIngredients[$j][2][0]."');";


			//file_put_contents('php://stderr',print_r(" ArrayIterator: ".$query."\n\n\n\n\n\n" ,TRUE));
			if (! mysql_query ( $query )) {
				die ( "{'error':'Error description3: ".mysql_error()."'}" );
			}
		}

		//Get  ID from the order

		$query = "SELECT ID FROM orders WHERE ID = ".$id."";
		$result = mysql_query ( $query );

		if (! $result) {
			die ( "{'error':'Error description4: ".mysql_error()."'}" );
		}

		file_put_contents('php://stderr',print_r(" ArrayIterator: \n\n\n\n\n\n" ,TRUE));
		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		header('location:../project/combos.html');
		echo $result;
	}

	/**
	 * Receives the ID and returns all the data from the order.
	 * */

	function getOrderData($pid){
		$id = $id;

		//Get all the data from the order

		$query = "SELECT * FROM orders WHERE ID = ".$id."";
		$result = mysql_query ( $query );

		if (! $result) {
			die ( "{'error':'Error description: ".mysql_error()."'}" );
		}

		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		echo $result;
	}

	function editOrder($orderId , $ingarray, $price){

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

		$ingredients = $ingarray;
		$totalPrice = $price;

		$query = "DELETE FROM order_ingredient WHERE order_id = '".$orderId."' AND ingredient_id IN( SELECT id FROM ingredients WHERE ingredients.available = '1')";
		file_put_contents('php://stderr',print_r(" Query 1 : ".$query."\n\n\n\n\n\n" ,TRUE));
		if (!mysql_query($query )){
			die ("{'error' : 'Error description:".mysql_error()." '}");
		}

		$query = "UPDATE orders SET total = '".$totalPrice."' WHERE id = '".$orderId."';";
		file_put_contents('php://stderr',print_r(" Query 2 : ".$query."\n\n\n\n\n\n" ,TRUE));
		if (!mysql_query($query )){
			die ("{'error' : 'Error description:".mysql_error()." '}");
		}


		for ($j = 0 ; $j < count($arrayIngredients); $j++){

			$query = "SELECT ID FROM ingredients WHERE type='".$arrayIngredients[$j][1]."' AND name='".$arrayIngredients[$j][0]."';";
			//file_put_contents('php://stderr',print_r(" sadfasfds: ".$query."\n\n\n\n\n\n" ,TRUE));
			$result = mysql_query ( $query );
			$result = mysql_fetch_assoc($result);
			$idIngredient = $result['ID'];

			$query = "INSERT INTO order_ingredient (order_id, ingredient_id, quantity) VALUES ('".$orderId."','".$idIngredient."','".$arrayIngredients[$j][2][0]."');";
			file_put_contents('php://stderr',print_r(" Query 3 : ".$query."\n\n\n\n\n\n" ,TRUE));

			//file_put_contents('php://stderr',print_r(" ArrayIterator: ".$query."\n\n\n\n\n\n" ,TRUE));
			if (! mysql_query ( $query )) {
				die ( "{'error':'Error description3: ".mysql_error()."'}" );
			}
		}
		header('location:../project/combos.html');
	}

	function deleteOrder($orderId){
	    $query = "DELETE FROM orders WHERE id=".$orderId.";";
	    file_put_contents('php://stderr',print_r(" request: ".$orderId."\n\n\n\n\n\n" ,TRUE));
	    if (! mysql_query ( $query )) {
	      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
	    }

	    header('location:../project/combos.html');
	  }


}


if (isset ($_REQUEST['co']) && isset($_POST['cid']) && isset($_POST['ing']) && isset($_POST['total'])){
	$order = new Order();
	$order->CreateOrder($_POST['cid'], $_POST['ing'], $_POST['total']);
}else if (isset($_REQUEST['god']) && isset($_REQUEST['id'])){
	$order = new Order();
	$order->getOrderData($_REQUEST['id']);
}else if (isset ($_REQUEST['uo']) && isset($_POST['oid']) && isset($_POST['ing']) && isset($_POST['total'])){
	$order = new Order();;
	$order->editOrder($_POST['oid'], $_POST['ing'], $_POST['total']);
}else if (isset ($_REQUEST['do']) && isset($_POST['order'])) {
  $order = new Order();
  $order->deleteOrder($_POST['order']);
}else{
	die ( "{'error':'Check params.'}" );
}
?>
