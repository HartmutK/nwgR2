<?php
function insert_wohnung( $ingut, $oldwohng, $newlage, $newbez ) {

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;
	include( 'insert_raum.php' ) ;

	if( $newlage == '' ) { $newlage = $whnglage ; }
	if( $newbez == '' ) { $newbez = $whngbezeichnung ; }

	$abf = "SELECT MAX( Reihenfolge ) AS MaxNr FROM $db_wohnung WHERE InGebaeude = $ingebaeude" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
	$rw = mysql_fetch_object( $erg ) ;
	$next_doc = 1 + $rw->MaxNr ;

	$frage2 = "INSERT INTO $db_wohnung (`WohnungID`, `InGutachten`, `InGebaeude`, `RevitBez`, `WEObjart`, `Bezeichnung`, `Lage`, `Widmung`, `Besichtigungsdatum`, `Zustand`,  
																			`Beschreibung`, `Bewertet`, `Regelwohnung`, `Miete`, `Nutzflaeche`, `Nutzwertanteil`, `Nebenraeume`, `RNW`, `RNWcalc`, `Reihenfolge` )
								VALUES( NULL, '$ingut', '$ingebaeude', '$revtbez', '$weobjart', '$newbez', '$newlage', '$widmung', '$besichtigung', '$whngzustand', 
												'$whngbeschreibung', '$bewertet', '$regelwhng', '$miete', '$nutzflaeche', '$nutzwertanteil', '$raumneben', '$rnwwohnung', '$rnwc', '$next_doc' )" ;
	$ergeb2 = mysql_query( $frage2 ) OR die( "Error: $frage2 <br>". mysql_error() ) ;
  $newwohng = mysql_insert_id() ;

	$frage2a = "SELECT * FROM $db_zuabwhng WHERE ZuAbWhng = $oldwohng" ;
	$ergeb2a = mysql_query( $frage2a )  OR die( "Error: $frage2a <br>". mysql_error() ) ;
	while( $row2a = mysql_fetch_object( $ergeb2a ) ) {
		$zuab_zuab		= $row2a->ZuAb_ZuAb ;
		$zuab_gruppe	= $row2a->Gruppe ;
		$zuab_kurz		= $row2a->ZuAbKurz ;
		$zuab_abschl	= $row2a->ZuAbschlag ;
		$zuab_min			= $row2a->Min ;
		$zuab_dflt		= $row2a->Dflt ;
		$zuab_max			= $row2a->Max ;
		$zuab_einh		= $row2a->Einheit ;
		$frage2b	= "INSERT INTO $db_zuabwhng (`ZuAbWhngID`, `Gut8en`, `ZuAbGbde`, `ZuAbWhng`, `ZuAb_ZuAb`, `Gruppe`, `ZuAbKurz`, `ZuAbschlag`, `Min`, `Dflt`, `Max`, `Einheit` ) 
									VALUES( NULL, '$ingut', '$zuAbgbde', '$newwohng', '$zuab_zuab', '$zuab_gruppe', '$zuab_kurz', '$zuab_abschl', '$zuab_min', '$zuab_dflt', '$zuab_max', '$zuab_einh' )" ;
		$ergeb2b = mysql_query( $frage2b )  OR die( "Error: $frage2b <br>". mysql_error() ) ;
		}

	$frage2a = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $oldwohng" ;
	$ergeb2a = mysql_query( $frage2a )  OR die( "Error: $frage2a <br>". mysql_error() ) ;
	while( $row2a = mysql_fetch_object( $ergeb2a ) ) {
		$zuxxid	= $row2a->ZuxxxID ;
//		$ingut	= $row2a->InGutachten ;
		$inwhng	= $row2a->InWohnung ;
		$revt		= $row2a->RevitID ;
		$zubbez	= $row2a->Bezeichnung ;
		$zubmin	= $row2a->Min;
		$zubxxx	= $row2a->Zuxxx ;
		$zubmax	= $row2a->Max ;
		$zubein	= $row2a->Einheit ;
		$zubeh	= $row2a->Zubehoer ;
		$flche	= $row2a->Flaeche ;
		$nw			= $row2a->NWpro_m2 ;
		$zublag	= $row2a->Lage ;
		$rpfleg	= $row2a->Rechtspfleger ;
		$gu			= $row2a->GanzUnten ;
		$frage2b = "INSERT INTO $db_whngzuxxx(`ZuxxxID`, `InGutachten`, `InWohnung`, `RevitID`, `Bezeichnung`, `Min`, `Zuxxx`, `Max`, `Einheit`, `Zubehoer`, 
																					`Flaeche`, `NWpro_m2`, `Lage`, `Rechtspfleger`, `GanzUnten` ) 
									VALUES( NULL, '$ingut', '$newwohng', '$revt', '$zubbez', '$zubmin', '$zubxxx', '$zubmax', '$zubein', '$zubeh', '$flche', '$nw', '$zublag', '$rpfleg', '$gu' )" ;
		$ergeb2b = mysql_query( $frage2b )  OR die( "Error: $frage2b <br>". mysql_error() ) ;
		}

	$frage2a = "SELECT Lage FROM $db_wohnung WHERE WohnungID = $oldwohng" ;
	$ergeb2a = mysql_query( $frage2a )  OR die( "Error: $frage2a <br>". mysql_error() ) ;
	$row2a = mysql_fetch_object( $ergeb2a ) ;
	$lagevorher = $row2a->Lage ;

	$frage2c = "SELECT * FROM $db_raum WHERE InWohnungID = $oldwohng" ;
	$ergeb2c = mysql_query( $frage2c )  OR die( "Error: $frage2c <br>". mysql_error() ) ;
	while( $rw2c = mysql_fetch_object( $ergeb2c ) ) {
		$raumid						= $rw2c->RaumID ;
		$revitid					= $rw2c->RevitID ;
		$inwohnungid			= $rw2c->InWohnungID ;
		$raumbezeichnung	= $rw2c->Bezeichnung ;
		$raumanmerkung		= $rw2c->Anmerkung ;

		$raumlage 				= $rw2c->Lage ;
		if( $raumlage == $lagevorher ) {
			$raumlage = $newlage ;
			}
		$raumart 					= $rw2c->Raumart ;
		$nebenraum				= $rw2c->Nebenraum ;
		$bewert						= $rw2c->Bewertung ;
		$bewerteinh				= $rw2c->BewertEinheit ;
		$flaeche					= $rw2c->Flaeche ;
		$nutzwert					= $rw2c->Nutzwert ;
		$nweinzel					= $rw2c->Einzelnutzwert ;
		$raumneben	 			= $rw2c->Nebenraum ;
		$reihenfolge 			= $rw2c->Reihenfolge ;
		insert_raum( $raumid, $newwohng, '' ) ;
		}
	}
?>
<!-- EOF -->