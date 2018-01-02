<?php
function insert_raum( $room, $wohnung, $roomart ) {

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;

 	if( $roomart == '' ) { $roomart = $raumart ; }

	$fragex1	= "INSERT INTO $db_raum ( `RaumID`, `RevitID`, `InWohnungID`, `Bezeichnung`, `Anmerkung`, `Lage`, `Raumart`, `Bewertung`, `BewertEinheit`, `Flaeche`, 
																			`Nutzwert`, `Einzelnutzwert`, `Nebenraum`, `Reihenfolge` )
								VALUES ( NULL, '$revitid', '$wohnung', '$raumbezeichnung', '$raumanmerkung', '$raumlage', '$roomart', '$bewert', '$bewertein', '$flaeche', 
													'$nutzwert', '$nweinzel', '$raumneben', '$reihenfolge' )" ;
	$ergebx1 = mysql_query( $fragex1 ) OR die( "Error: $fragex1 <br>". mysql_error() ) ;
  $newroom = mysql_insert_id() ;

	$frage1a = "SELECT * FROM $db_abmessung WHERE vonRaumID = $room" ;
	$ergeb1a = mysql_query( $frage1a ) OR die( "Error: $frage1a <br>". mysql_error() ) ;
	while( $row1a = mysql_fetch_object( $ergeb1a ) ) {
		$massid			= $row1a->MassID ;
		$vonraumid	= $row1a->vonRaumID ;
		$raum_a			= $row1a->A ;
		$raum_b			= $row1a->B ;
		$raum_c			= $row1a->C ;
		$raum_t			= $row1a->T ;
		$form				= $row1a->Form ;
		$addieren		= $row1a->Addieren ;
		$flche			= $row1a->Flaeche ;

		$frage1b = "INSERT INTO $db_abmessung (`MassID`, `vonRaumID`, `A`, `B`, `C`, `T`, `Form`, `Addieren`, `Flaeche` ) 
									VALUES( NULL, '$newroom', '$raum_a', '$raum_b', '$raum_c', '$raum_t', '$form', '$addieren', '$flche' )" ;
		$ergeb1b = mysql_query( $frage1b )  OR die( "Error: $frage1b <br>". mysql_error() ) ;
		}
	}
?>
<!-- EOF -->