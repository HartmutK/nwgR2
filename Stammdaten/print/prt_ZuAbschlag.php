<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	require( '../../fpdf_181/fpdf.php' ) ;
	require( '../../GutachtenDruck/Subroutines/rotation.php' ) ;
	include( '../../GutachtenDruck/Subroutines/PDFextendsFPDF.php' ) ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abf = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg ) ) {
		include( '../../php/get_db_gutachten.php' ) ;
		}

	$fn = 'Zu_Abschlaege.PDF' ;

// ------------------------------------------------------------------------
	$head = array( iconv('UTF-8', 'windows-1252', 'Kürzel' ), 'Beschreibung' );
	$w=array( 20, 105 ) ;

	$nixhead = true ;

	$pdf=new PDF( 'P', 'mm', 'A4' );
	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
	$pdf->AddPage() ;
	$pdf->SetFont( $schrift, 'B', $h1size ) ;
	$pdf->Cell( $w[ 0 ], $zeilenhoehe, $head[ 0 ], 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 1 ], $zeilenhoehe, $head[ 1 ], 0, 1, 'L' ) ;
	$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
	$pdf->SetFont( $schrift, '', $h1size ) ;
	$pdf->Ln() ;

	$abfrage = "SELECT Kuerzel, Kommentar FROM $db_zuabschlag WHERE Aktiv ORDER BY Gruppe, Kuerzel" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$kurz	= $row->Kuerzel ;
		$bezg	= $row->Kommentar ;
		$pdf->Cell( $w[ 0 ], $zeilenhoehe, $kurz, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, $bezg, 0, 1, 'L' ) ;
		}

// ------------------------------------------------------------
// Ausgabe
	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
?>
<!-- EOF -->