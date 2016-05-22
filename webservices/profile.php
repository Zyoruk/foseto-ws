<?php

include_once 'connect_sql.php';

$cookieName = "userInfo";

$query = "SELECT name, email, nick FROM user WHERE id =" . $_COOKIE[$cookieName];

$result = mysql_query ( $query );
$result = mysql_fetch_assoc($result);
$result = json_encode($result);
echo ($result);

?>
