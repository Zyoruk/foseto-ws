<?php
include_once 'connect_sql.php';

$final='[' ;
$query = "SELECT * FROM ingredients WHERE available = '1'";
$result = mysql_query ( $query );
while ($row = mysql_fetch_assoc($result)) {
  $row = json_encode($row);
  $final = $final.$row.",";
}
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
echo($final);
?>
