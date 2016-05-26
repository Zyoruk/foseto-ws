<?php
include_once 'connect_sql.php';

if (isset ($_REQUEST['oid'])){
	$oid = $_REQUEST['oid'];
}

$final='[' ;
$query = "SELECT * FROM order_ingredient INNER JOIN ingredients ON order_ingredient.ingredient_id=ingredients.id WHERE order_ingredient.order_id = '".$oid."';";
//file_put_contents('php://stderr',print_r(" AAAAA: ".$query."\n\n\n\n\n\n" ,TRUE));
$result = mysql_query ( $query );
while($row = mysql_fetch_assoc($result)) {
	  $row = json_encode($row);
	  $final = $final.$row.",";
}
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
echo($final);
//file_put_contents('php://stderr',print_r(" final: ".$final."\n\n\n\n\n\n" ,TRUE));*/

?>
