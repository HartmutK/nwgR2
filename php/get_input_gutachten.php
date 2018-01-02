<?php
function get_input_gutachten( $insert ) {

	include( 'DBselect.php' ) ;
	include( 'GlobalProject.php' ) ;

	$bezeichng		= $_POST[ 'bezeichng' ] ;
	$anmelden			= $_SESSION[ 'anmelden' ] ;
	$bearbeiter		= $anmelden[ 'id' ] ;;
	$abfrage = "SELECT FirmaID FROM $db_user WHERE UserID = '$bearbeiter'" ;
	$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	if( $row = mysql_fetch_object( $ergeb ) ) {
		$faid = $row->FirmaID ;
		}
	else {
		$faid = 1 ;
		}

	if( isset( $_POST[ 'abgeschl' ] ) ) { $abgeschl= true ; } else { $abgeschl= false ; }
	$gz						= $_POST[ 'gz' ] ;
	$index				= $_POST[ 'index' ] ;
	$ez						= $_POST[ 'ez' ] ;
	$gericht			= $_POST[ 'gericht' ] ;
	$stichtag			= $_POST[ 'stichtag' ] ;
	$eigner				= $_POST[ 'eigner' ] ;
	$auftrag			= $_POST[ 'auftrag' ] ;
	$zweck				= $_POST[ 'zweck' ] ;
	$besichtigt		= $_POST[ 'besichtigt' ] ;

	$gemeinde = '' ;
	$gericht = '' ;
	$grundbuch		= $_POST[ 'grundbuch' ] ;
	$abfrage = "SELECT * FROM $db_kastral WHERE Nummer = '$grundbuch'" ;
	$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb ) ) {
		$gemeinde = $row->Gemeinde ;
		$gericht = $row->Gericht ;
		}
	$ort					= '' ;
	$gst_nr				= $_POST[ 'gst_nr' ] ;
	$plz					= $_POST[ 'plz' ] ;
	$abfrage = "SELECT * FROM $db_lpo WHERE PLZ = '$plz'" ;
	$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb ) ) {
		$ort = $row->Ort ;
		}

	$str					= $_POST[ 'str' ] ;
	$liegenschaft	= $_POST[ 'liegenschaft' ] ;
	$zufahrtlage	= $_POST[ 'zufahrtlage' ] ;
	$beschreibung	= $_POST[ 'beschreibung' ] ;
	$gutachtn			= $_POST[ 'gutachtn' ] ;
	$praeambel 		= $_POST[ 'praeambel' ] ;
	if( $praeambel == '' ) {
		$abfrage  = "SELECT * FROM $db_txt WHERE TxtArt = 'Epilog'" ;
		$ergeb = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb ) ) {
			$praeambel = $row->txt ;
			}
		}

	$gesetzl			= $_POST[ 'gesetz' ] ;
	$empfehlgen		= $_POST[ 'selection' ] ;
	$nichtbefund	= $_POST[ 'nichtbefund' ] ;
	$lage					= $_POST[ 'lage' ] ;
	$anbindung		= $_POST[ 'anbindung' ] ;
	$zugang				= $_POST[ 'zugang' ] ;
	$lagegrundstk	= $_POST[ 'lagegrundstk' ] ;
	$nachbarbau		= $_POST[ 'nachbarbau' ] ;
	$ausrichtung	= $_POST[ 'ausrichtung' ] ;
	$oeffentlich	= $_POST[ 'oeffentlich' ] ;
	$gruenraum		= $_POST[ 'gruenraum' ] ;
	$sonstiges		= $_POST[ 'sonstiges' ] ;

	if( $insert ) {
		$abfrage	= "INSERT INTO $db_gutachten (`GutachtenID`, `Bezeichnung`, `Firma`, `Bearbeiter`, `Abgeschlossen`, `GZ`, `GZindex`, `EZ`, `Bezirksgericht`, `Bewertungsstichtag`, 
													`Eigentuemer`, `Auftraggeber`, `Zweck`, `BesichtigtAm`, `Grundbuch`, `GST_Nr`, `PLZ`, `Ort`, `Strasse`, `Liegenschaft`, `ZufahrtLage`, `Beschreibung`, 
													`Gutachten`, `Praeambel`, `GesetzlGrundl`, `Empfehlungen`, `NichtBefundet`, `Lage`, `Anbindung`, `Zugang`, `LageGrundstk`, `Nachbarbau`, 
													`Ausrichtung`, `Oeffentliches`, `Gruenraum`, `Sonstiges` )
								VALUES( NULL, '$bezeichng', '$faid', '$bearbeiter', false, '$gz', '$index', '$ez', '$gericht', '$stichtag', 
													'$eigner', '$auftrag', '$zweck', '$besichtigt', '$grundbuch',  '$gst_nr','$plz', '$ort', '$str', '$liegenschaft', '$zufahrtlage', '$beschreibung', 
													'$gutachtn', '$praeambel', '$gesetzl', '$empfehlgen', '$nichtbefund', '$lage', '$anbindung', '$zugang', '$lagegrundstk', '$nachbarbau', 
													'$ausrichtung', '$oeffentlich', '$gruenraum', '$sonstiges' )" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$rs = mysql_query("SELECT LAST_INSERT_ID()");
		$newgutachten = mysql_result($rs,0) ;

		$abfrage= "SELECT * FROM $db_ugsource WHERE Aktiv AND Deflt" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$group	= $row->Gruppe ;
			$was		= $row->Beschreibg ;
			$txt		= $row->Txt ;
			$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$newgutachten', '$group', '', '$was', '', '', '' )" ;
			$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
			$rw = mysql_fetch_object( $ergeb ) ;
			if( $group == 1 ) {
				$frage2 = "UPDATE $db_gutachten SET Gutachten = '$txt', GesetzlGrundl = '$was' WHERE GutachtenID = $newgutachten" ;
				$ergeb2 = mysql_query( $frage2 ) OR die( "Error: $frage2 <br>". mysql_error() ) ;
				}
			}
		}
	else {
		$abfrage = "UPDATE $db_gutachten SET Bezeichnung = '$bezeichng', Abgeschlossen = '$abgeschl', GZ = '$gz', GZindex = '$index', EZ = '$ez', 
										Bezirksgericht = '$gericht', Bewertungsstichtag = '$stichtag', Eigentuemer = '$eigner', Auftraggeber = '$auftrag', Zweck = '$zweck', 
										BesichtigtAm = '$besichtigt', Grundbuch = '$grundbuch', GST_Nr = '$gst_nr', PLZ = '$plz', Ort = '$ort', Strasse = '$str', Liegenschaft = '$liegenschaft', 
										Beschreibung = '$beschreibung'
										
									WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Gutachtendaten ge&auml;ndert' ;
	  $newgutachten = 0 ;
		}
	return(  $newgutachten ) ;
	}
?>
<!-- EOF -->