<?php
//include 'connect_sql.php';

class User{
	function login($email, $pass){

		//No deberia estar aqui
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "erick"; // add your password
		$dbname = 'foseto';

		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );
		//Hasta aqui

		$query = "SELECT id FROM user WHERE email ='".$email."' AND  pass = '".$pass."' ;";
		$result = mysql_query($query,$conn);
		$cookieError="error";
		if (!$result){
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}else if (mysql_num_rows($result) == 0){
			$cookieErrorValue="Usuario o contrasena incorrectos";
			setcookie($cookieError,$cookieErrorValue,time()+3600,"/");
			header('location:../project/index.html');
			die ( "{'error' : 'Username or Password wrong'}" );
		}

		$result = mysql_fetch_assoc($result);
		$result = json_encode($result);
		$json = json_decode($result);

		//cookie
		$cookieName = "userInfo";
		$cookieValue= $json->id;
		$cookieErrorValue="";
		setcookie($cookieError,$cookieErrorValue,time()+3600,"/");
		setcookie($cookieName, $cookieValue);

		header('location:../project/index.html');
	}

	function register($name, $nick, $email, $pass){

		//No deberia estar aqui
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "erick"; // add your password
		$dbname = 'foseto';

		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );
		//Hasta aqui

		$query = "SELECT 1 FROM user WHERE email = '".$email."';";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0){
			$query = "INSERT INTO user (nick,name,email,pass) VALUES ('".$nick."','".$name."','".$email."','".$pass."')";
			header('location:../project/ordenes.html');
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
		$query = $query . "WHERE id = '" .$uid . "';";

		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}

	}

	function viewProfileInfo($uid, $result){
		$query = "SELECT name, email, nick FROM user WHERE id = '" .$uid . "';";
		$result = mysql_query ( $query );
		echo $result;
		return $result;
	}

	function modifyNick($nick){

		//No deberia estar aqui
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "erick"; // add your password
		$dbname = 'foseto';

		$cookieName = "userInfo";

		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );
		//Hasta aqui

		$query = "UPDATE user SET nick = '".$nick."' WHERE id =" .$_COOKIE[$cookieName].";";
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}

		header('location:../project/perfil.html');

	}

	function modifyPass($pass){

		//No deberia estar aqui
		$servername = "localhost";
		$username = "root"; // add your mysql username
		$password = "erick"; // add your password
		$dbname = 'foseto';

		$cookieName = "userInfo";

		// Create connection
		$conn = mysql_connect ( $servername, $username, $password, TRUE );
		mysql_select_db ($dbname, $conn );
		//Hasta aqui

		$query = "UPDATE user SET pass = '".$pass."' WHERE id =" .$_COOKIE[$cookieName].";";
		if (! mysql_query ( $query )) {
			die ( "{'error' : 'Error description:" . mysql_error ( $conn ) . " '}" );
		}

		header('location:../project/perfil.html');

	}
}
if (isset ( $_REQUEST ["log"] ) && isset ( $_POST ["email"] ) && isset ( $_POST ["pass"] )) {
	$user = new User ();
	$user->login ( $_POST ["email"], md5($_POST ["pass"]) );

}	else if (isset ( $_REQUEST ["reg"] ) && isset ( $_POST ["name"] ) && isset ( $_POST ["nick"] ) && isset ( $_POST ["email"] ) && isset ( $_POST ["pass"] )) {
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
} else if (isset($_REQUEST ["mn"]) && isset($_POST ["nick"] )){
	$user = new User();
	$user->modifyNick($_POST ["nick"]);
} else if (isset($_REQUEST ["mp"]) && isset($_POST ["pass"] )){
	$user = new User();
	$user->modifyPass(md5($_POST ["pass"]));
}
