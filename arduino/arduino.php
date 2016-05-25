<?php
if (isset($_GET['action'])){
	
	include "php_serial.class.php";
	
	$serial =  new phpSerial ();
	$serial->setDevice("/dev/ttyACM0");
	if ($_GET['action'] == "on"){
		$serial->sendMessage ("1\r") ;
	}else if ($_GET['action'] == "off"){
		$serial->sendMessage ("0\r") ;
	}
	
}

?>
