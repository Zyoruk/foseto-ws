<?php

$servername = "localhost";
$username = "root"; // add your mysql username
$password = "erick"; // add your password
$dbname = 'foseto';

if (isset ($_REQUEST['cid'])){
	$cid = $_REQUEST['cid'];
}

// Create connection
$conn = mysql_connect ( $servername, $username, $password, TRUE );
mysql_select_db ($dbname, $conn );

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
file_put_contents('php://stderr',print_r(" final: ".$final."\n\n\n\n\n\n" ,TRUE));

?>
