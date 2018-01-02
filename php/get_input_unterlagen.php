<?php
function get_input_unterlagen( $welche ) {

	include( '../php/GlobalProject.php' ) ;

	switch( $welche ) {
		case "1":
			$nr		= $_POST[ 'save1' ] ;
			$wann	= $_POST[ 'wann1' ] ;
			$was	= $_POST[ 'was1' ] ;
			$wer	= $_POST[ 'wer1' ] ;
			$wo		= $_POST[ 'wo1' ] ;
			$akte	= $_POST[ 'akte1' ] ;
			$frei	= $_POST[ 'frei1' ] ;
			$db		= $db_baubehgut8 ;
			break ;
		case "2":
			$nr		= $_POST[ 'save2' ] ;
			$wann	= $_POST[ 'wann2' ] ;
			$was	= $_POST[ 'was2' ] ;
			$wer	= $_POST[ 'wer2' ] ;
			$wo		= $_POST[ 'wo2' ] ;
			$akte	= $_POST[ 'akte2' ] ;
			$frei	= $_POST[ 'frei2' ] ;
			$db		= $db_vertvereinb ;
			break ;
		case "3":
			$nr		= $_POST[ 'save3' ] ;
			$wann	= $_POST[ 'wann3' ] ;
			$was	= $_POST[ 'was3' ] ;
			$wer	= $_POST[ 'wer3' ] ;
			$wo		= $_POST[ 'wo3' ] ;
			$akte	= $_POST[ 'akte3' ] ;
			$frei	= $_POST[ 'frei3' ] ;
			$db		= $db_entscheide ;
			break ;
		default:
			$nr		= $_POST[ 'save4' ] ;
			$wann	= $_POST[ 'wann4' ] ;
			$was	= $_POST[ 'was4' ] ;
			$wer	= $_POST[ 'wer4' ] ;
			$wo		= $_POST[ 'wo4' ] ;
			$akte	= $_POST[ 'akte4' ] ;
			$frei	= $_POST[ 'frei4' ] ;
			$db		= $db_sonst ;
			break ;
		}  // switch $seite

	$tableid 	= $_SESSION[ 'tableid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	return ;
	}
?>