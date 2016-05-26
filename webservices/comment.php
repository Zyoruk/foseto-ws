<?php
include_once 'connect_sql.php';

$final='[' ;
$query = "SELECT * FROM commentary INNER JOIN user ON user.id=commentary.uid;";
$result = mysql_query ( $query );
while($row = mysql_fetch_assoc($result)) {
    $row = json_encode($row);
    $final = $final.$row.",";
}
$final = substr($final,0,-1);
$final = $final."]";
$final = json_encode($final);
echo($final);
//file_put_contents('php://stderr',print_r(" final: ".$final."\n\n\n\n\n\n" ,TRUE));

?>
