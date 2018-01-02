<?php
// ------------------------------------------------------------------------
// --------------- C.1 Allgemeines ---------------------------------------------------------
//	$pdf->SetAutoPageBreak( true, 1.8 ) ;

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'C.1  Allgemeines' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $h2size ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy ) ; 
  $pdf->MultiCell( $textbreite, $zeilenhoehe, $gutachtn ) ;
	$pdf->Ln( ) ; 
// END C.1 Allgemeines 
// ------------------------------------------------------------
?>
<!-- EOF -->