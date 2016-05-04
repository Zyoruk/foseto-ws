<?php
include 'connect_sql.php';
class User{
	function login($nick, $pass){
		
		$query = "SELECT id FROM user WHERE nick =" .$nick." pass = ".$pass.";";
		$result = mysql_query($query);
		
		if (!$result){
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}else if (mysql_num_rows($result) == 0){
			die ( "{'error' : 'Username or Password wrong'}" );
		}
		
		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		
		echo $result;
	}
	
	function register($username, $nick, $email, $pass){
		$query = "SELECT 1 FROM user WHERE nick = " .$nick.";";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0){
			$query = "INSERT INTO user (".$nick.",".$username.",".$email.",".$pass.")";
			
			if (! mysql_query ( $query )) {
				die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
			}
		}else{
			die ( "{'error' : 'Nick already exists'}" );
		}
	}
	
	function checkUserInfo($uid){
		$query = "SELECT nicñ , name , email FROM user WHERE id = ".$uid.";"; ;
		$result = mysql_query ( $query );
		if (! $result) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		echo $result;
	}
	
	function checkUserRecentOrders($uid){
		
		$query = "SELECT id as uid, total FROM orders WHERE client = ".$uid;
		
		if(mysql_query($query)){
			if (mysql_num_rows($conn) == 0){
				die ("{'message':'no recent orders'}");
			}
		}else{
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		
		$query = "SELECT id as uid, total FROM orders WHERE client = ".$uid;
		$query = $query . " INNER JOIN SELECT ingredient_id FROM order_ingredient WHERE order_id = uid";
		$query = $query . " INNER JOIN SELECT name FROM ingredients WHERE ingredients.id = ingredient_id";
		$query = $query . " GROUP BY (uid);";
		
		$result = mysql_query ( $query );
		
		if (! $result) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		
		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		
		echo $result;
	}
	
	function checkUserActiveOrders($uid){
		$query = "SELECT id as uid, total FROM orders WHERE client = ".$uid." AND status = O";
		if(mysql_query($query)){
			if (mysql_num_rows($conn) == 0){
				die ("{'message':'no active orders'}");
			}
		}else{
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		$query = "SELECT id as uid, total FROM orders WHERE client = ".$uid." AND status = O";
		$query = $query . " INNER JOIN SELECT ingredient_id FROM order_ingredient WHERE order_id = uid";
		$query = $query . " INNER JOIN SELECT name FROM ingredients WHERE ingredients.id = ingredient_id";
		$query = $query . " GROUP BY (uid);";
		
		$result = mysql_query ( $query );
		
		if (! $result) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		
		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		
		echo $result;
	}
	
	function changeUserData($uid , $array){
		$query = "UPDATE user SET";
		$first = TRUE;
		foreach ( $array as $key => $value ) {
			if ($first) {
				$query = $query . $key . "=" . $value;
				$first = FALSE;
			} else {
				$query = $query . "," . $key . "=" . $value;
			}
		}
		$query = $query . "WHERE id = " .$uid . ";";
		
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}
		
	}
}

if (isset ( $_REQUEST ["log"] ) && isset ( $_POST ["nick"] ) && isset ( $_POST ["pass"] )) {	
	$user = new User ();
	$user->login ( $_POST ["nick"], md5($_POST ["pass"]) );
	
} else if (isset ( $_POST ["reg"] ) && isset ( $_POST ["name"] ) && isset ( $_REQUEST ["nick"] ) && isset ( $_POST ["email"] ) && isset ( $_POST ["pass"] )) {
	$user = new User ();
	$user->register ( $_POST ["name"], $_POST ["nick"], $_POST ["email"],md5( $_POST ["pass"]) );
	
} else if (isset($_REQUEST["cui"]) && isset($_POST["uid"])){
	$user = new User ();
	$user->checkUserInfo($_POST["uid"]);
	
} else if (isset($_REQUEST["cro"]) && isset($_POST["uid"])){
	$user = new User ();
	$user->checkUserRecentOrders($_POST["uid"]);
	
} else if (isset($_REQUEST ["cao"]) && isset($_POST ["uid"])) {
	$user = new User ();
	$user->checkUserActiveOrders ( $_POST ["uid"] );
} else if (isset($_REQUEST ["cud"]) && isset($_POST ["uid"] )) {
	$user = new User ();
	$user->changeUserData ( $_POST ["uid"] );
}