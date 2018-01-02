<?php
	$frage = "DELETE FROM WohnungZuschlag WHERE InWohnung = $wohng" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	$frage = "DELETE FROM ZuAbWohnung WHERE ZuAbWhng = $wohng" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

		// Räume und Abmesungen
	$frage1 = "SELECT RaumID FROM Raeume WHERE InWohnungID = $wohng" ;
	$ergeb1 = mysql_query( $frage1 ) OR die( "Error: $frage1 <br>". mysql_error() ) ;
	while( $rw1 = mysql_fetch_object( $ergeb1 ) ) {
		$raum = $rw1->RaumID ;
		include( 'raum.php' );
		}

	$frage = "DELETE FROM Wohnungen WHERE WohnungId = $wohng" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
?>
<!-- EOF -->