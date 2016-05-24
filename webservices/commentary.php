<?php
include_once 'connect_sql.php';

class commentary{
	function comment ($uid , $text, $rating){
	
		$query  = "INSERT INTO commentary (uid, text, rating) VALUES (".$uid.",". $text.",".$rating.");";
	
		if (!mysql_query($query)){
			echo "{'Error description':".mysql_error ($conn)."}";
		}
	
	}
	
	function viewCommentaries(){
		$query = "SELECT * FROM commentary;";
		$result = mysql_query($query);
		if (!$result ){
			echo "{'Error description':".mysql_error ($conn)."}";
		}
		$result = json_encode ($result);
		echo $result;
	}
	
	function deleteCommentary($cuid){
		$query = "DELETE FROM commentary WHERE cuid =".$cuid.";";
		if (!mysql_query($query)){
			echo "{'Error description':".mysql_error ($conn)."}";
		}
	}
}

if (isset ($_REQUEST["c"]) && isset ($_REQUEST["uid"]) && isset ($_REQUEST["text"]) && isset ($_REQUEST["rating"])){
	$comm = new Commentary ();
	$comm->comment ($_REQUEST["uid"], $_REQUEST["text"], $_REQUEST["rating"]) ;
	
}else if (isset ($_REQUEST["vc"])){
	$comm = new Commentary ();
	$comm->viewCommentaries () ;
	
}else if (isset($_REQUEST["dc"]) && isset($_REQUEST["cuid"])){
	$comm = new Commentary ();
	$comm->deleteCommentary($_REQUEST["cuid"]);
}
?>