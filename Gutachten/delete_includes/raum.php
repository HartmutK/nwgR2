<?php
	$frage = "DELETE FROM Raummasse WHERE vonRaumID = $raum" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

	$frage = "DELETE FROM Raeume WHERE RaumID = '$raum'" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
?>
<!-- EOF -->