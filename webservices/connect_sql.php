<?php
/**
 * Connects to mysql.
 * @author Zyoruk
 * In order to make this work, you must edit the script in order to connect to your data base
 * using your login. Must change values servername, username, password, dbname.
 * @return An error if didn't connect.
 */

$servername = "localhost";
$username = "root"; // add your mysql username
$password = "erick"; // add your password
$dbname = 'foseto';

// Create connection
$conn = mysql_connect ( $servername, $username, $password, TRUE );
mysql_select_db ($dbname, $conn );

// Check connection
if (! $conn) {
	die ( "{error:'Connection failed:" . mysql_connect_error ())."'}";
}

/*header('location:../project/perfil.html');*/

?>
