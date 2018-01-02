<?php
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT Auftraggeber FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	$auftrag = $row->Auftraggeber ;

	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.1  AuftraggeberInnen' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	if( $auftrag != 0 ) {
		$abfrage = "SELECT * FROM $db_auftraggeb WHERE ID = $auftrag" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$fma1		= $row->Firma1 ;
		$fma2		= $row->Firma2 ;
		$tit_v	= $row->Titel_vorne ;
		$nm			= $row->Name ;
		$vnm		= $row->Vorname ;
		$tit_h	= $row->Titel_hinten ;
		$posit	= $row->Position ;
		$stra		= $row->Strasse ;
		$lnd		= $row->Land ;
		$lpo		= $row->PLZ . ' ' .  $row->Ort ;
		$tlf		= $row->Telefon ;
		$mbl		= $row->Mobil ;
		$ml			= $row->eMail ;
		$weh		= $row->WWW ;

		if( $tit_v	!= '' ) $wer = $tit_v . ' ' ;
		if( $vnm 		!= '' ) $wer = $wer . ' ' . $vnm ;
		if( $nm 		!= '' ) $wer = $wer . ' ' . $nm ;
		if( $tit_h	!= '' ) $wer = $wer . ', ' . $tit_h ;
		if( $lnd		!= '' ) $lpo = $lnd . '-' . $lpo ;

		if( $fma1 != '' ) $pdf->Cell( 0, $zeilenhoehe, $fma1, 0, 1, 'L' ) ;
		if( $fma2 != '' ) $pdf->Cell( 0, $zeilenhoehe, $fma2, 0, 1, 'L' ) ;
		if( $wer	!= '' ) $pdf->Cell( 0, $zeilenhoehe, $wer, 0, 1, 'L' ) ;
		if( $stra	!= '' ) $pdf->Cell( 0, $zeilenhoehe, $stra, 0, 1, 'L' ) ;
		if( $lpo	!= '' ) $pdf->Cell( 0, $zeilenhoehe, $lpo, 0, 1, 'L' ) ;
		if( $tlf	!= '' ) {
			$pdf->Cell( 20, $zeilenhoehe, 'Telefon:', 0, 0, 'L' ) ;
			$pdf->Cell( 40, $zeilenhoehe, $tlf, 0, 1, 'L' ) ;
			}
		if( $mbl	!= '' ) {
			$pdf->Cell( 20, $zeilenhoehe, 'Mobil:', 0, 0, 'L' ) ;
			$pdf->Cell( 40, $zeilenhoehe, $mbl, 0, 1, 'L' ) ;
			}
		if( $ml	!= '' ) {
			$pdf->Cell( 20, $zeilenhoehe, 'E-Mail:', 0, 0, 'L' ) ;
			$pdf->Cell( 40, $zeilenhoehe, $ml, 0, 1, 'L' ) ;
			}
		if( $weh	!= '' ) $pdf->Cell( 0, $zeilenhoehe, $weh, 0, 1, 'L' ) ;
		}
?>
<!-- EOF -->
