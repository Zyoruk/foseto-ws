<?php
include_once 'connect_sql.php';

if (isset ($_REQUEST['cid'])){
	$cid = $_REQUEST['cid'];
}

$final='[' ;
$query = "SELECT * FROM orders INNER JOIN order_ingredient ON orders.clientId ='".$cid."' AND orders.id=order_ingredient.order_id INNER JOIN ingredients on ingredients.id=order_ingredient.ingredient_id WHERE orders.status ='P';";
file_put_contents('php://stderr',print_r(" AAAAA: ".$query."\n\n\n\n\n\n" ,TRUE));
$result = mysql_query ( $query );
while($row = mysql_fetch_assoc($result)) {
	  $row = json_encode($row);
	  $final = $final.$row.",";
}
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
echo($final);
file_put_contents('php://stderr',print_r(" finalAAA: ".$final."\n\n\n\n\n\n" ,TRUE));

?>
