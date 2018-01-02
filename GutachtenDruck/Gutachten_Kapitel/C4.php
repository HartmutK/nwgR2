<?php
// collect DATA
	$gutachtenid	= $_SESSION[ 'gutachtenid' ] ;
	$frage2 = "SELECT Bezeichnung, Lage FROM $db_wohnung WHERE InGutachten = $gutachtenid AND Regelwohnung" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergeb2 ) ;
	if( !$row ) {
		$nixrw = true ;
		}
	else {
		$nixrw = false ;
		$whngbez	= $row->Bezeichnung . ' (Regelwohnung)' ;
		$whglage 	= $row->Lage ;
		}		

// RNW holen
	unset( $rnw ) ;

	$frage2 = "SELECT DISTINCT Lage, Widmung, RNW FROM $db_wohnung WHERE InGutachten = $gutachtenid AND Widmung != 'Wohnung'" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb2 ) ) {
		$whnglage 				= $row->Lage ;
		$widmung					= $row->Widmung ;
		$rnwwohnung				= $row->RNW ;
		$rnw [ ] = array( 'widm'=>$widmung, 'lage'=>$whnglage, 'rw'=>$rnwwohnung ) ;
		}		

// ------------------------------------------------------------------------
// PRINT
	$head = array( 'WE-Objekt', 'Lage', 'RNW' );
	$w = array( 75, 30, 15 ) ;
	$w=array( 80, 30,  $textbreite - 110 ) ;

	$pos = $h2hoehe + 2 * $zeilenhoehe + count( $rnw ) + 2 ;
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'C.4  Regelnutzwerte' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;

	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( $w[0], $zeilenhoehe, $head[ 0 ], '', 0, 'L' ) ;
	$pdf->Cell( $w[1], $zeilenhoehe, $head[ 1 ], '', 0, 'L' ) ;
	$pdf->Cell( $w[2], $zeilenhoehe, $head[ 2 ], '', 1, 'R' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy); 
	$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy);

	if( $nixrw ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Regelwohnung nicht definiert', '', 1, 'L' ) ;
		}
	else {
		$pdf->Cell( $w[0], $zeilenhoehe, $whngbez, '', 0, 'L' ) ;
		$pdf->Cell( $w[1], $zeilenhoehe, $whglage, '', 0, 'L' ) ;
		$pdf->Cell( $w[2], $zeilenhoehe, '1,00', '', 1, 'R' ) ;
		}

	$i = 0 ;
	$anz_rnw = count( $rnw ) ;

	while( $i < $anz_rnw ) {
//		$wid	= $rnw [ $i ][ 'wid' ] ;
		$widm	= $rnw [ $i ][ 'widm' ] ;
		$lage	= $rnw [ $i ][ 'lage' ] ;
		$rw		= $rnw [ $i ][ 'rw' ] ;
		$rw		= number_format( round( $rw, 2 ), 2, ',', '.' ) ;

		$pdf->Cell( $w[0], $zeilenhoehe, $widm, '', 0, 'L' ) ;
		$pdf->Cell( $w[1], $zeilenhoehe, $lage, '', 0, 'L' ) ;
		$pdf->Cell( $w[2], $zeilenhoehe, $rw, '', 1, 'R' ) ;

		$i++ ;
		}
	$pdf->Ln () ;
// END C.4 Regelnutzwerte 
?>
<!-- EOF -->
