<?php
	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
//	$pdf->AddPage() ;
	$pdf->SetFont( $schrift, 'B', $h1size ) ;
	$pdf->SetX( $pagemargin_L ) ;
	$ueberschrift = 'A  Allgemeine Angaben' ;
	$pdf->Cell( $pagemargin_L, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 0 ) ;

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.1  AuftraggeberInnen' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $auftrag, '', 1, 'L' ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 
  $pdf->MultiCell( $textbreite, $zeilenhoehe, $auftrag );
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 

	$pdf->Ln() ;
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.2  AuftragnehmerInnen' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$abfrage = "SELECT * FROM $db_user WHERE master" ;
	$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_user.php' ) ;
		$ma = $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
		}
	$pdf->Cell( $textbreite, $zeilenhoehe, $fabez, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $ma, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $faplz . ' ' . $faort . ' . ' . $fastr, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $firmennr . ' . ' . $fatel, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $fawww . ' . ' . $userwww, '', 1, 'L' ) ;

	$pdf->Ln() ;
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.3  Zweck und Gegenstand des Gutachtens' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 
	$pdf->MultiCell( $textbreite, $zeilenhoehe, $zweck );
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 
	if( $zweckgrund <> '' ) { 
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy);
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$hlp = iconv('UTF-8', 'windows-1252', 'Begründung' ) ;
		$pdf->Cell( $textbreite, $zeilenhoehe, $hlp, '', 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $textbreite, $zeilenhoehe, $zweckgrund );
		}

	$pdf->Ln() ;
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.4  Bewertungsstichtag' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$stichtag	= date( "Y.m.d", strtotime( $stichtag ) ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $stichtag, '', 1, 'L' ) ;
?>
<!-- EOF -->
