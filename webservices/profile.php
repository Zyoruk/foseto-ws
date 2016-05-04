<?php

$servername = "localhost";
$username = "root"; // add your mysql username
$password = "erick"; // add your password
$dbname = 'foseto';

// Create connection
$conn = mysql_connect ( $servername, $username, $password, TRUE );
mysql_select_db ($dbname, $conn );


$query = "SELECT name, email, nick FROM user WHERE id =" . $_COOKIE[$cookieName];
print_r("defghyerthdrtyherthertherthert");

$result = mysql_query ( $query );
$result = mysql_fetch_assoc($result);
$result = json_encode($result);
echo ($result);

?>
