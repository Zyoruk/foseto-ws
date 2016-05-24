<?php
include_once 'connect_sql.php';

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
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
echo($final);
?>
