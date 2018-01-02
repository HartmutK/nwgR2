<?php
		$frage = "DELETE FROM Gebaeude WHERE GebaeudeID = $gebde" ;
		$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
?>
<!-- EOF -->