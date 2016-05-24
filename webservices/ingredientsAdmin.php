<?php

$servername = "localhost";
$username = "root"; // add your mysql username
$password = "1807"; // add your password
$dbname = 'foseto';


// Create connection
$conn = mysql_connect ( $servername, $username, $password, TRUE );
mysql_select_db ($dbname, $conn );

$cont = "SELECT COUNT(id) FROM ingredients;";
$num = mysql_query( $cont );
$num = mysql_fetch_assoc($num);
$num = $num["COUNT(id)"];
$num =(int)$num;

$j = 1;
$final='[' ;
for ($i=1; $j <= $num; $i++) {
  $query = "SELECT * FROM ingredients WHERE id ='".$i."';";
  $result = mysql_query ( $query );
  $result = mysql_fetch_assoc($result);
  $result = json_encode($result);

  if($result != 'false'){
    $j++;
    $final = $final.$result.",";
  }

}
file_put_contents('php://stderr',print_r(" EEE: ".$final."\n\n\n\n\n\n" ,TRUE));
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
echo($final);


?>
