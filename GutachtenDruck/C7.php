<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	$inhalt = $_REQUEST[ 'wie' ] ;

	if( $inhalt = 1 OR $inhalt = 3 ) {
		$vorabzug = true ;
		}
	else { 
		$vorabzug = false ;
		}

	require( '../fpdf_181/fpdf.php' ) ;
	require( 'Subroutines/rotation.php' ) ;
	include( 'Subroutines/PDFextendsFPDF.php' ) ;

	$nixhead 			= false ;
	$nixfoot 			= false ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abf = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	$abf0 = "SELECT * FROM $db_user WHERE master" ;
	$erg0 = mysql_query( $abf0 )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg0 ) ) {
		include( '../php/get_db_user.php' ) ;
		$ma = 'MA = -' . $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
		}

	$fn = 'NWG-' . $gz . '-' . $index . '.PDF' ;

	$kopftext = $plz . ' ' . $ort . ', ' . $str ;
	$fusstext = 'GZ: ' . $gz . '-' . $index . '-' . $stichtag . '-' . $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
	$mitarbeiter = $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
	$stichtag	= date( $dateform, strtotime( $stichtag ) ) ;

	$pdf=new PDF();

	$pdf->startPageNums();
	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
	$pdf->AliasNbPages();
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$pdf->AddPage() ;

	include( 'Gutachten_Kapitel/C7.php' ) ;

// END C.7 Zu- / Abschlagsmatrix 
// ------------------------------------------------------------

// Ausgabe
	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
?>
<!-- EOF -->