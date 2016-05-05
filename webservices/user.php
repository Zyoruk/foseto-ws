<?php

class User{
	function login($nick, $pass){
		
	}
	
	function register($username, $nick, $email, $pass){
		
	}
	
	function checkUserInfo($uid){
		
	}
	
	function checkUserRecentOrders($uid){
		
	}
	
	function checkUserActiveOrders($uid){
		
	}
}

if (isset ( $_REQUEST ["log"] ) && isset ( $_POST ["nick"] ) && isset ( $_POST ["pass"] )) {	
	$user = new User ();
	$user->login ( $_POST ["nick"], $_POST ["pass"] );
	
} else if (isset ( $_POST ["reg"] ) && isset ( $_POST ["name"] ) && isset ( $_REQUEST ["nick"] ) && isset ( $_POST ["email"] ) && isset ( $_POST ["pass"] )) {
	$user = new User ();
	$user->register ( $_POST ["name"], $_POST ["nick"], $_POST ["email"], $_POST ["pass"] );
	
} else if ($_REQUEST["cui"] && $_POST["uid"]){
	$user = new User ();
	$user->checkUserInfo($_POST["uid"]);
	
} else if ($_REQUEST["cro"] && $_POST["uid"]){
	$user = new User ();
	$user->checkUserRecentOrders($_POST["uid"]);
	
} else if ($_REQUEST["cao"] && $_POST["uid"]){
	$user = new User ();
	$user->checkUserActiveOrders($_POST["uid"]);
}