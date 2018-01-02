<?php
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT Bewertungsstichtag FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	$stichtag = $row->Bewertungsstichtag ;

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.4  Bewertungsstichtag' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	if( $stichtag != '0000-00-00' ) {
		$stich	= date( "Y.m.d", strtotime( $stichtag ) ) ;
		}
	else {
		$stich	= '-' ;
		}
	$pdf->Cell( $textbreite, $zeilenhoehe, $stich, '', 1, 'L' ) ;
?>
<!-- EOF -->
