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

$cont = "SELECT COUNT(id) FROM orders WHERE clientId='".$cid."';";
$num = mysql_query( $cont );
$num = mysql_fetch_assoc($num);
$num = $num["COUNT(id)"];
$num =(int)$num;

$final='[' ;
for ($i=1; $i <= $num; $i++) {
  $query = "SELECT * FROM orders WHERE clientId='".$cid."';";
  $result = mysql_query ( $query );
  $result = mysql_fetch_assoc($result);
  $result = json_encode($result);
  $final = $final.$result.",";
}
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
/*file_put_contents('php://stderr',print_r(" final: ".$final."\n\n\n\n\n\n" ,TRUE));
file_put_contents('php://stderr',print_r(" cid: ".$cid."\n\n\n\n\n\n" ,TRUE));
file_put_contents('php://stderr',print_r(" num: ".$num."\n\n\n\n\n\n" ,TRUE));*/
echo($final);
//file_put_contents('php://stderr',print_r(" final: ".$final."\n\n\n\n\n\n" ,TRUE));

?>
