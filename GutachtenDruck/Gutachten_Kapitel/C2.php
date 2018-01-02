<?php
// ------------------------------------------------------------------------
// --------------- C.2 Allgemeinflächen -----------------------------------
// collect DATA
	unset( $table_c2 ) ;
	unset( $we_objekte ) ;

	$widmungen = array() ;
	$abfrage = "SELECT Widmung, COUNT( `Widmung` ) as anz_Widmung FROM Wohnungen LEFT JOIN Gebaeude ON Wohnungen.InGebaeude = Gebaeude.GebaeudeID\n"
    . "	WHERE Gebaeude.InGutachtenID = $gutachtenid GROUP BY Widmung";
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$widmg = $row->Widmung ;
		$anz = $row->anz_Widmung ;
		$widmungen [ ] = array( 'widmg'=>$widmg, 'anz'=>$anz ) ;
		}

	$bewertung = array() ;
	$abfrage = "SELECT Widmung, COUNT( `Bewertet` ) as anz_Bewertet FROM Wohnungen LEFT JOIN Gebaeude ON Wohnungen.InGebaeude = Gebaeude.GebaeudeID\n"
    . "	WHERE Gebaeude.InGutachtenID = $gutachtenid AND Wohnungen.Bewertet GROUP BY Widmung";
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$widmg = $row->Widmung ;
		$anz = $row->anz_Bewertet ;
		$bewertung [ ] = array( 'widmg'=>$widmg, 'anz'=>$anz ) ;
		}

	$anz_objekte = 0 ;
	$we_objekte = array() ;
	$cnt = 0 ;
	$anz = count( $widmungen ) ;
	while( $cnt < $anz ) {
		$widmg = $widmungen [ $cnt ][ 'widmg' ] ;
		$anz1  = $widmungen [ $cnt ][ 'anz' ] ;
		$anz_objekte = $anz_objekte + $anz1 ;

		$cnt1 = 0 ;
		$anz_b = count( $bewertung ) ;
		$anz2 = 0 ;
		while( $cnt1 < $anz_b ) {
			$widm = $bewertung [ $cnt1 ][ 'widmg' ] ;
			if( $widmg == $widm ) {
				$anz2 = $bewertung [ $cnt1 ][ 'anz' ] ;
				$cnt1 = $anz_b ;
				}
			$cnt1++ ;
			}
		$we_objekte [ ] = array( 'sp0'=>$widmg, 'sp1'=>$anz1, 'sp2'=>$anz2 ) ;
		$cnt++ ;
		}

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$frage  = "SELECT Anmerkung_AF FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb ) ) {
		$anmerk = $rw->Anmerkung_AF ;
		}

	$pos = $h2hoehe + $zeilenhoehe + 2 ;
	$frage = "SELECT * FROM $db_allgflaeche WHERE InGutachtenID = $gutachtenid ORDER BY Reihenfolge" ;
	$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb ) ) {
		$lge 			= $rw->Lage ;
		$bereich	= $rw->Bereich ;
		$allgfl		= $rw->Allgflaeche ;
		$table_c2 [ ] = array( 'sp1'=>$lge, 'sp2'=>$bereich, 'sp3'=>$allgfl ) ;
		$pos = $pos + $zeilenhoehe ;
		} // Allgemeinflächen 

	$txtoben = '' ;
	$frage = "SELECT txt FROM $db_txt WHERE TxtArt = 'AF-oben'" ;
	$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb ) ) {
		$txtoben = $rw->txt ;
		}

	$txtunten = '' ;
	$frage = "SELECT txt FROM $db_txt WHERE TxtArt = 'AF-unten'" ;
	$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb ) ) {
		$txtunten = $rw->txt ;
		}

// ------------------------------------------------------------------------
// PRINT
	$head=array( 'Lage', iconv('UTF-8', 'windows-1252', 'Allgemeinfläche' ), iconv('UTF-8', 'windows-1252', 'Fläche in m²' ) );
	$w=array( 25, 60,  $textbreite - 85 ) ;

	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = iconv('UTF-8', 'windows-1252', 'C.2  Allgemeinflächen' ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $h2size ) ;

	$posx = $pagemargin_L ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 
  $pdf->MultiCell( $textbreite, $zeilenhoehe, $txtoben );

	$pdf->Ln( ) ;
	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( $w[0], $zeilenhoehe, $head[ 0 ], '', 0, 'L' ) ;
	$pdf->Cell( $w[1], $zeilenhoehe, $head[ 1 ], '', 0, 'L' ) ;
	$pdf->Cell( $w[2], $zeilenhoehe, $head[ 2 ], '', 1, 'R' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy); 
	$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $linemargin ;
	$pdf->setXY( $posx, $posy);

	$cnt = 0 ;
	$anz = count( $table_c2 ) ;
	while( $cnt < $anz ) {
		$sp1 = $table_c2 [ $cnt ][ 'sp1' ] ;
		$sp2 = $table_c2 [ $cnt ][ 'sp2' ] ;
		$sp3 = $table_c2 [ $cnt ][ 'sp3' ] ;
		$pdf->Cell( $w[0], $zeilenhoehe, $sp1, '', 0, 'L' ) ;
		$pdf->Cell( $w[1], $zeilenhoehe, $sp2, '', 0, 'L' ) ;
		$sp3 = number_format( round( $sp3, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $w[2], $zeilenhoehe, $sp3, '', 1, 'R' ) ;

		$cnt++ ;
		} // while( $cnt < $anz )

	if( $anmerk != '' ) {
		$pos = 9 * $zeilenhoehe ;
		}
	else {
		$pos = 3 * $zeilenhoehe ;
		}
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}
	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( $w[0], $zeilenhoehe, 'Hinweis', '', 1, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy); 
  $pdf->MultiCell( $textbreite, $zeilenhoehe, $txtunten );

	if( $anmerk != '' ) {
		$pdf->Ln( ) ; 
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$pdf->Cell( $w[0], $zeilenhoehe, 'Anmerkung', '', 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $textbreite, $zeilenhoehe, $anmerk );
		}
	$pdf->Ln( ) ; 

// END C.2 Allgemeinflächen
// ------------------------------------------------------------
?>
<!-- EOF -->