<?php
// ------------------------------------------------------------------------
// --------------- C.11  Epilog -------------------------------------------

	$pos = $h2hoehe + 13 * $zeilenhoehe ;
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}


	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = iconv('UTF-8', 'windows-1252', 'C.11  Epilog' ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $h2size ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 
  $pdf->MultiCell( $textbreite, $zeilenhoehe, $praeambel );
	$pdf->Ln( ); 
// END C.11  Epilog Allgemeines 
// ------------------------------------------------------------
?>
<!-- EOF -->