<?php
// collect DATA
	unset( $table_c6 ) ;
	$table_c6 = array( ) ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$frage3 = "SELECT DISTINCT Bezeichnung, Zuxxx, Einheit FROM $db_whngzuxxx WHERE InGutachten = $gutachtenid AND !Zubehoer ORDER BY Bezeichnung" ;
	$ergeb3 = mysql_query( $frage3 )  OR die( "Error: $frage3 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb3 ) ) {
		$zubbez	= $row->Bezeichnung ;
		$zubxxx	= $row->Zuxxx ;
		$zubein	= $row->Einheit ;
		$table_c6 [ ] = array( 'bez'=>$zubbez, 'wert'=>$zubxxx, 'einh'=>$zubein ) ;
		} // while

// print
	$head=array( 'Bezeichnung', 'Wert' );
	$w=array( 140, 35 ) ;

	$merkbez = '' ;
	$merkwert = '' ;

	$anz = count( $table_c6 ) ;

	$pos = $h2hoehe + 2 * $zeilenhoehe + $anz + 2 ;
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = iconv('UTF-8', 'windows-1252', 'C.6  Zuschläge' ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $h2size ) ;

	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( $w[0], $zeilenhoehe, $head[ 0 ], '', 0, 'L' ) ;
	$pdf->Cell( $w[1], $zeilenhoehe, $head[ 1 ], '', 1, 'R' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy); 
	$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy);

	$cnt = 0 ;
	
	$anz = count( $table_c6 ) ;
	sort( $table_c6 ) ;
	while( $cnt < $anz ) {
		$bez	= $table_c6 [ $cnt ][ 'bez' ] ;
		$wert = $table_c6 [ $cnt ][ 'wert' ] ;
		$einh = $table_c6 [ $cnt ][ 'einh' ] ;
		if( $einh != '' ) {
			$wert = $wert . ' ' . $einh ;
			}
		if( $bez != $merkbez OR $wert != $merkwert ) {
	 	  $pdf->Cell( $w[0], $zeilenhoehe, $bez, '', 0, 'L' ) ;
			$wert = number_format( round( $wert, 2 ), 2, ',', '.' ) ;
			$wert = $wert . ' ' . $einh ;
	 	  $pdf->Cell( $w[1], $zeilenhoehe, $wert, '', 1, 'R' ) ;
			$merkbez = $bez ;
			$merkwert = $wert ;
			}
		$cnt++ ;
		} // while( $cnt < $anz )
	$pdf->Ln( ) ;
?>
<!-- EOF -->