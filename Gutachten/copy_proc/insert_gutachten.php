<?php
function insert_gutachten( $oldgutachten, $gzahl, $indx ) {

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;
	include( 'insert_gebaude.php' ) ;
	include( 'insert_raum.php' ) ;

	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $oldgutachten" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_gutachten.php' ) ;
		}

	$frage = "INSERT INTO $db_gutachten (`GutachtenID`, `Bezeichnung`, `RevitBez`, `Bearbeiter`, `Abgeschlossen`, `GZ`, `GZindex`, `EZ`, `Bezirksgericht`, `Bewertungsstichtag`, 
												`Eigentuemer`, `Auftraggeber`, `Zweck`, `Zweckgrund`, `BesichtigtAm`, `Grundbuch`, `GST_Nr`, `PLZ`, `Ort`, `Strasse`, `Liegenschaft`, `ZufahrtLage`, `Beschreibung`, 
												`Gutachten`, `Praeambel`, `GesetzlGrundl`, `Empfehlungen`, `NichtBefundet`, `Lage`, `Anbindung`, `Zugang`, `LageGrundstk`, `Nachbarbau`, 
												`Ausrichtung`, `Oeffentliches`, `Gruenraum`, `Sonstiges`, `Ausstattung`, `GesNFL`, `Anmerkung_AF` )
							VALUES( NULL, '$bezeichng', '$revit', '$bearbeiter', false, '$gzahl', '$indx', '$ez', '$gericht', '$stichtag', 
												'$eigner', '$auftrag', '$zweck', '$zweckgrund', '$besichtigt', '$grundbuch',  '$gst_nr','$plz', '$ort', '$str', '$liegenschaft', '$zufahrtlage', '$beschreibung', 
												'$gutachtn', '$praeambel', '$gesetzl', '$empfehlgen', '$nichtbefund', '$lage', '$anbindung', '$zugang', '$lagegrundstk', '$nachbarbau', 
												'$ausrichtung', '$oeffentlich', '$gruenraum', '$sonstiges', '$ausstattung', '$gesnflche', '$anmerkungaf' )" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
  $newgutachten = mysql_insert_id() ;

	$frage2 = "SELECT * FROM $db_allgflaeche WHERE InGutachtenID = $oldgutachten" ;
	$ergeb2 = mysql_query( $frage2 ) OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $ergeb2 ) ) {
		$w0 = $rw2->RevitID ;
		$w1 = $rw2->Lage ;
		$w2 = $rw2->Bereich ;
		$w3 = $rw2->Allgflaeche ;
		$w4 = $rw2->Sortiert ;
		$frage4b = "INSERT INTO $db_allgflaeche (`AllgID`, `InGutachtenID`, `RevitID`, `Lage`, `Bereich`, `Allgflaeche`, `Sortiert` ) VALUES( NULL, '$newgutachten', '$w0', '$w1', '$w2', '$w3', '$w4' )" ;
		$ergeb4b = mysql_query( $frage4b ) OR die( "Error: $frage4b <br>". mysql_error() ) ;
		} // Allgemeinflächen

	$frage = "SELECT * FROM $db_dokumente WHERE GutachtenID = $oldgutachten" ;
	$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb ) ) {
		include( '../../php/get_db_dokumente.php' ) ;
		$weg = '../../upload/' . $newgutachten ;
		if( !file_exists( $weg ) ) {
			mkdir( $weg, 0777, true) ;
			}

		$olddoc = '../../upload/' . $oldgutachten . '/' . $dok ;
		$newweg = $weg . '/' ;
		$newdoc = $newweg . $dok ;
		if ( !copy( $olddoc, $newdoc ) ) {
//			echo "copy $olddoc to $newdoc schlug fehl!<br>" ;
			}
		else {
			$frage1a	= "INSERT INTO $db_dokumente (`DokID`, `GutachtenID`, `GebaeudeID`, `WohnungID`, `RaumID`, `Bezeichnung`, `Beschreibung`, `Seite1Foto`, `Titelbild`, 
																							`Drucken`, `Grundbuch`, `Bild`, `Grundriss`, `Lageplan`, `DokumentenPfad`, `Dokument`, `Reihenfolge` ) 
														VALUES( NULL, '$newgutachten', 0, 0, 0, '$bezeichng', '$beschreibung', '$titelbild', '$seite1', 
																							'$drucken', '$grundbuch', '$bild', '$grundriss', '$lageplan', '$newweg', '$dok', '$reihenfolge' )" ;
			$ergeb1a = mysql_query( $frage1a ) OR die( "Error: $frage1a <br>". mysql_error() ) ;
			}
		}

	$frage = "SELECT * FROM $db_ugsink WHERE Gut8en = $oldgutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb ) ) {
		$group	= $rw->Gruppe ;
		$when		= $rw->Wann ;
		$what		= $rw->Was ;
		$who		= $rw->Wer ;
		$where	= $rw->Wo ;
		$akt		= $rw->Akte ;
		$frage1a	= "INSERT INTO $db_ugsink(`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$newgutachten', '$group', '$when', '$what', '$who', '$where', '$akt' )" ;
		$ergeb1a = mysql_query( $frage1a ) OR die( "Error: $frage1a <br>". mysql_error() ) ;
		}

	$abfrage = "SELECT * FROM $db_gebaeude WHERE InGutachtenID = $oldgutachten" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_gebaeude.php' ) ;
		insert_gebaude( $newgutachten, $gebbezeich ) ;
		}

	$abfrage = "SELECT * FROM $db_wohnung WHERE InGutachten = $oldgutachten" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_wohnung.php' ) ;

		$ingut = $newgutachten ;
		$oldwohng = $wohnungid ;
		$newlage = '' ;
		$newbez = '' ;

		if( $newlage == '' ) { $newlage = $whnglage ; }
		if( $newbez == '' ) { $newbez = $whngbezeichnung ; }
	
		$frage2 = "INSERT INTO $db_wohnung (`WohnungID`, `InGutachten`, `InGebaeude`, `RevitBez`, `WEObjart`, `Bezeichnung`, `Lage`, `Widmung`, `Besichtigungsdatum`, `Zustand`, 
																				`Beschreibung`, `Bewertet`, `Regelwohnung`, `Miete`, `Nutzflaeche`, `Nutzwertanteil`, `RNW`, `RNWcalc`, `Reihenfolge` )
									VALUES( NULL, '$ingut', '$ingebaeude', '$revtbez', '$weobjart', '$newbez', '$newlage', '$widmung', '$besichtigung', '$whngzustand', '$whngbeschreibung', 
													'$bewertet', '$regelwhng', '$miete', '$nutzflaeche', '$nutzwertanteil', '$rnwwohnung', '$rnwc', '$reihenfolge' )" ;
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
//			$ingut	= $row2a->InGutachten ;
			$inwhng	= $row2a->InWohnung ;
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
			$frage2b = "INSERT INTO $db_whngzuxxx(`ZuxxxID`, `InGutachten`, `InWohnung`, `Bezeichnung`, `Min`, `Zuxxx`, `Max`, `Einheit`, `Zubehoer`, `Flaeche`, `NWpro_m2`, `Lage`, 
																						`Rechtspfleger`, `GanzUnten` ) 
										VALUES( NULL, '$ingut', '$newwohng', '$zubbez', '$zubmin', '$zubxxx', '$zubmax', '$zubein', '$zubeh', '$flche', '$nw', '$zublag', '$rpfleg', '$gu' )" ;
			$ergeb2b = mysql_query( $frage2b )  OR die( "Error: $frage2b <br>". mysql_error() ) ;
			}

		$frage2c = "SELECT * FROM $db_raum WHERE InWohnungID = $oldwohng" ;
		$ergeb2c = mysql_query( $frage2c )  OR die( "Error: $frage2c <br>". mysql_error() ) ;
		while( $rw2c = mysql_fetch_object( $ergeb2c ) ) {
			$raumid						= $rw2c->RaumID ;
			$revitid					= $row->RevitID ;
			$inwohnungid			= $rw2c->InWohnungID ;
			$raumbezeichnung	= $rw2c->Bezeichnung ;
			$raumanmerkung		= $rw2c->Anmerkung ;
			$raumlage 				= $rw2c->Lage ;
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
	}
?>
<!-- EOF -->