<?php
// ------------- B.3 Ortsbesichtigung -------------------------------
	include( "../Gutachten/Subroutines/make_liste_wohnungen.php" ) ;

// Column titles and width
	$header=array( 'Objektarten', 'Lage', 'Nummer', 'Widmung', 'Bewertet' );

	$w=array( 50, 20, 35, 55, 14 ) ;

	$pos = $h2hoehe + $zeilenhoehe + count( $wohnungen ) * $zeilenhoehe + 2 ;
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}

	$ueberschrift = 'B.3  Ortsbesichtigungen' ;
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	for($j=0;$j<count($header);$j++) $pdf->Cell( $w[$j], $zeilenhoehe, $header[ $j ], '', 0, 'L' ) ;
	$pdf->Ln( ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy); 
	$pdf->Cell( $textbreite+1, 1, '', 'T', 1, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy);
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$neu = true ;
	$merk = '' ;
	$i = 0 ;
	$anz = count( $wohnungen ) ;
	while( $i < $anz ) {
		$haus = $wohnungen [ $i ][ 'weobjart' ] ;
		if( $merk != $haus ) {
			if( !$neu ) {
				$pdf->Ln( $zeilenhoehe ) ;
				}
			$neu = false ;
	    $merk = $haus ;
			}
		else {
	    $haus = '' ;
			}

		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
		$pdf->Ln();
		$pdf->MultiCell( $w[ 0 ], $zeilenhoehe, $haus, '', 'L' );
		$posy = $pdf->GetY() ;
		$pdf->setXY( $posx + $w[ 0 ], $posy - $zeilenhoehe ); 

    $pdf->Cell( $w[ 1 ], $zeilenhoehe, $wohnungen [ $i ][ 'lage' ] ) ;
    $pdf->Cell( $w[ 2 ], $zeilenhoehe, $wohnungen [ $i ][ 'nummer' ] ) ;
    $pdf->Cell( $w[ 3 ], $zeilenhoehe, $wohnungen [ $i ][ 'widmg' ] ) ;
		if( $wohnungen [ $i ][ 'bewertet' ] ) {
			$bew = 'Ja' ;
			}
		else {
			$bew = 'Nein' ;
			}
    $pdf->Cell( $w[ 4 ], $zeilenhoehe, $bew, '', 0, 'C' ) ;
		$pdf->setXY( $posx, $posy - $zeilenhoehe ); 
    $i++ ;
		}
	$pdf->Ln( 2*$zeilenhoehe );
// ------------- ENDE Ortsbesichtigung -------------------------------
?>
<!-- EOF -->
