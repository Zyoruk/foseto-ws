
<?php
include_once 'connect_sql.php';

class User{
  function login($email, $pass){
    $query = "SELECT id FROM user WHERE email ='".$email."' AND  pass = '".$pass."' ;";
    $result = mysql_query($query);
    $cookieError="error";
    if (!$result){
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }else if (mysql_num_rows($result) == 0){
      $cookieErrorValue="Usuario o contrasena incorrectos";
      setcookie($cookieError,$cookieErrorValue,0,"/");
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
    setcookie($cookieError,$cookieErrorValue,0,"/");
    setcookie($cookieName, $cookieValue,time()+3600,"/");
    header('location:../project/index.html');
  }
  function register($name, $nick, $email, $pass){
    $cookieError="error_reg";
    $query = "SELECT 1 FROM user WHERE email = '".$email."';";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0){
      $query = "INSERT INTO user (nick,name,email,pass) VALUES ('".$nick."','".$name."','".$email."','".$pass."')";
      $cookieErrorValue="";
      setcookie($cookieError,$cookieErrorValue,0,"/");
      header('location:../project/index.html');
      if (! mysql_query ( $query )) {
        die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
      }
    }else{
      $cookieErrorValue="Correo ya existe";
      setcookie($cookieError,$cookieErrorValue,0,"/");
      header('location:../project/index.html#menu2');
      die ( "{'error' : 'Nick already exists'}" );
    }
  }
  function checkUserInfo($uid){
    $query = "SELECT nicñ , name , email FROM user WHERE id = ".$uid.";"; ;
    $result = mysql_query ( $query );
    if (! $result) {
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }
    $result = mysql_fetch_assoc($result);
    $result = json_encode($result);
    echo $result;
  }
  function checkUserRecentOrders($uid){
    $query = "SELECT id as uid, total FROM orders WHERE client = ".$uid;
    if(mysql_query($query)){
      if (mysql_num_rows( ) == 0){
        die ("{'message':'no recent orders'}");
      }
    }else{
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }
    $query = "SELECT id as uid, total FROM orders WHERE client = ".$uid;
    $query = $query . " INNER JOIN SELECT ingredient_id FROM order_ingredient WHERE order_id = uid";
    $query = $query . " INNER JOIN SELECT name FROM ingredients WHERE ingredients.id = ingredient_id";
    $query = $query . " GROUP BY (uid);";
    $result = mysql_query ( $query );
    if (! $result) {
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }
    $result = mysql_fetch_assoc($result);
    $result = json_encode($result);
    echo $result;
  }
  function checkUserActiveOrders($uid){
    $query = "SELECT id as uid, total FROM orders WHERE client = ".$uid." AND status = O";
    if(mysql_query($query)){
      if (mysql_num_rows( ) == 0){
        die ("{'message':'no active orders'}");
      }
    }else{
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }
    $query = "SELECT id as uid, total FROM orders WHERE client = ".$uid." AND status = O";
    $query = $query . " INNER JOIN SELECT ingredient_id FROM order_ingredient WHERE order_id = uid";
    $query = $query . " INNER JOIN SELECT name FROM ingredients WHERE ingredients.id = ingredient_id";
    $query = $query . " GROUP BY (uid);";
    $result = mysql_query ( $query );
    if (! $result) {
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
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
      die ( "{'error' : 'Error description:" . mysql_error (  ) . " '}" );
    }
  }
  function viewProfileInfo($uid, $result){
    $query = "SELECT name, email, nick FROM user WHERE id = '" .$uid . "';";
    $result = mysql_query ( $query );
    echo $result;
    return $result;
  }
  function modifyNick($nick){
    $cookieName="userInfo";
    $query = "UPDATE user SET nick = '".$nick."' WHERE id =" .$_COOKIE[$cookieName].";";
    if (! mysql_query ( $query )) {
      die ( "{'error' : 'Error description:" . mysql_error ( ) . " '}" );
    }
    $cookieMod="modify";
    $cookieValueMod = 1;
    setcookie($cookieMod,$cookieValueMod,0,"/");
    header('location:../project/perfil.html');
  }
  function modifyName($name){
    $cookieName="userInfo";
    $query = "UPDATE user SET name = '".$name."' WHERE id =" .$_COOKIE[$cookieName].";";
    if (! mysql_query ( $query )) {
      die ( "{'error' : 'Error description:" . mysql_error ( ) . " '}" );
    }

    $cookieMod="modify";
    $cookieValueMod = 1;
    setcookie($cookieMod,$cookieValueMod,0,"/");
    header('location:../project/perfil.html');
  }
  function modifyPass($pass){
    $cookieName="userInfo";
    $query = "UPDATE user SET pass = '".$pass."' WHERE id =" .$_COOKIE[$cookieName].";";
    if (! mysql_query ( $query )) {
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }
    $cookieMod="modify";
    $cookieValueMod = 1;
    setcookie($cookieMod,$cookieValueMod,0,"/");
    header('location:../project/perfil.html');
  }

  function postComment($uid, $comment, $rating){
    $query = "INSERT INTO commentary (uid, text, rating) VALUES('".$uid."','".$comment."','".$rating."') ";
    if (! mysql_query ( $query )) {
      die ( "{'error' : 'Error description:" . mysql_error (   ) . " '}" );
    }
  }
}

if (isset ( $_REQUEST ["log"] ) && isset ( $_POST ["email"] ) && isset ( $_POST ["pass"] )) {
  $user = new User ();
  $user->login ( $_POST ["email"], md5($_POST ["pass"]) );
}  else if (isset ( $_REQUEST ["reg"] ) && isset ( $_POST ["name"] ) && isset ( $_POST ["nick"] ) && isset ( $_POST ["email"] ) && isset ( $_POST ["pass"] )) {
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
} else if (isset($_REQUEST ["mna"]) && isset($_POST ["name"] )){
  $user = new User();
  $user->modifyName($_POST ["name"]);
} else if(isset($_REQUEST ["pc"]) && isset($_POST ["uid"] )  && isset($_POST ["tx"] ) && isset($_POST ["rt"] )){
  $user = new User();
  $user->postComment($_POST ["uid"], $_POST ["tx"], $_POST ["rt"]);
}
