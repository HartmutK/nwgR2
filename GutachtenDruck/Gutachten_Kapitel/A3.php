<?php
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
?>
<!-- EOF -->
