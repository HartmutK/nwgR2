<?php
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	unset( $wohnungen ) ;
	$abfrag1 = "SELECT * FROM $db_wohnung WHERE InGutachten=$gutachtenid ORDER BY Reihenfolge" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebn1 ) ) {
		include( "../php/get_db_wohnung.php" ) ;
		if( $ingebaeude > 0 ) {
			$abf = "SELECT Bezeichnung FROM $db_gebaeude WHERE GebaeudeID = $ingebaeude" ;
			$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
			$rw1 = mysql_fetch_object( $erg ) ;
			$gebaud = $rw1->Bezeichnung ;
			}
		else {
			$gebaud = '' ;
			}
		if( $whngbeschreibung != '' ) {
			$widmung = $whngbeschreibung ;
			$whngbeschreibung = '' ;
			}
		$wohnungen [ ] = array( 'we_objid'=>$wohnungid, 'gut8'=>$ingutachten, 'gebd'=>$gebaud, 'weobjart'=>$weobjart, 'lage'=>$whnglage, 
														'nummer'=>$whngbezeichnung, 'widmg'=>$widmung, 'anmerk'=>$whngbeschreibung, 'besichtigt'=>$besichtigung, 'bewertet'=>$bewertet ) ;
		}
?>
<!-- EOF -->