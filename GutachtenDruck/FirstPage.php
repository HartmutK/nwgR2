<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	require( '../fpdf_181/fpdf.php' ) ;
	require( 'Subroutines/rotation.php' ) ;
	include( 'Subroutines/PDFextendsFPDF.php' ) ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abf = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( $abgeschl ) {
		$vorabzug = false ;
		}
	else { 
		$vorabzug = true ;
		}
	$nixfoot = true ;

	$fn = 'NWG-' . $gz . '-' . $index . '.PDF' ;

// ------------------------------------------------------------------------
// --------------- Deckblatt ---------------------------------------------------------
	$pdf=new PDF( 'P', 'mm', 'A4' );
	$nixhead = true ;
	$nixfoot = true ;

	include( 'Gutachten_Kapitel/FirstPage.php' ) ;

// END Legende 
// ------------------------------------------------------------

// ------------------------------------------------------------
// Ausgabe
	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
?>
<!-- EOF -->