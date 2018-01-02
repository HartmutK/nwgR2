<?php
// ZuAbschläge holen -> Spaltenüberschriften
	unset( $_SESSION[ 'zuabschlag' ] ) ;
	$_SESSION[ 'zuabschlag' ] = array() ;

	unset( $zuabschlag ) ;
	$zuabschlag = array( ) ;
	
	$abfrage = "SELECT DISTINCT ZuAbKurz FROM $db_zuabwhng WHERE Gut8en = $gutachtenid ORDER BY ZuAbKurz" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$kurzel = $row->ZuAbKurz ;
		$zuabschlag[ ] = array( 'kurz'=>$kurzel ) ;
		}
	$_SESSION[ 'zuabschlag' ] = $zuabschlag ;

	unset( $legend ) ;
	$legend = array( ) ;
	$i = 0 ;
	$anz = count( $zuabschlag ) ;
	while( $i < $anz ) {
		$kurzel = $zuabschlag [ $i ][ 'kurz' ] ;
		$frage1 = "SELECT Kommentar, Wieviel, Einheit FROM $db_zuabschlag WHERE Aktiv AND Kuerzel = '$kurzel'" ;
		$ergeb1 = mysql_query( $frage1 )  OR die( "Error: $frage1 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb1 ) ) {
			$komment = $row->Kommentar ;
			$wieviel = $row->Wieviel ;
			$einheit = $row->Einheit ;
			if( $einheit != '' ) {
				$wieviel = $wieviel . ' ' . $einheit . ' ' ;
				}
		$legend [ ] = array( 'kurz'=>$kurzel, 'wieviel'=>$wieviel, 'komment'=>$komment ) ;
		$i++ ;
			}
		}

// Wohnungen im Gebäude holen
	unset( $_SESSION[ 'wohnungen' ] ) ;
	$_SESSION[ 'wohnungen' ] = array() ;
	unset( $wohnungen ) ;
	$wohnungen = array( ) ;

	$frage2 = "SELECT WohnungID, Bezeichnung, Lage FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb2 ) ) {
		$wohngid = $rw->WohnungID ;
		$wohnung = $rw->Bezeichnung ;
		$whglage = $rw->Lage ;
		$wohnung = $wohnung . ', ' . $whglage ;
		$wohnungen [ ] = array( 'wohngid'=>$wohngid, 'wohnung'=>$wohnung ) ;
		}
	$_SESSION[ 'wohnungen' ] = $wohnungen ;

// i = ZuAbschläge, ALLE
// j = Wohnungen
// ------------------------------------------------------------------------
// ---------------- C.7 Zu- / Abschlagsmatrix --------------------------------------------------------

	$pos = $h2hoehe + count( $legend ) * $zeilenhoehe + 2 ;
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'C.7  Zu-/Abschlagsmatrix' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;

	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$merkz1 = '' ;
	$noab = true ;
	$nozu = true ;
	$i = 0 ;
	$anz = count( $legend ) ;
	while( $i < $anz ) {
		$kurzel = $legend [ $i ][ 'kurz' ] ;
		$z1 = substr( $kurzel, 0, 1 ) ;
		$wieviel = $legend [ $i ][ 'wieviel' ] ;
		$komment = $legend [ $i ][ 'komment' ] ;

		if( $z1 != $merkz1 ) {
			$merkz1 = $z1 ;
			if( $z1 == 'Z' AND $nozu ) {
				$nozu = false ;
				$pdf->Ln( ); 
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( 30, $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Zuschläge' ), 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				}
			if( $z1 == 'A' AND $noab ) {
				$noab = false ;
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( 30, $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Abschläge' ), 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				}
			}

		$pdf->Cell( 15, $zeilenhoehe, $kurzel, 0, 0, 'L' ) ;
		$pdf->Cell( 25, $zeilenhoehe, $wieviel, 0, 0, 'R' ) ;
		$pdf->Cell( 60, $zeilenhoehe, $komment, 0, 1, 'L' ) ;
		$i++ ;
		}

	$zeile = $zeilenhoehe - 2 ;
	$pos = $zeilenhoehe + count( $wohnungen ) * $zeile ;
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}

	$pdf->Ln( ) ; 

	$pdf->SetFont( $schrift, '', $h2size ) ;
	$pdf->SetFont( $schrift, '', 6 ) ;

	$erste = true ;
	$merkwohnung = '' ;

	$pdf->Cell( 30, $zeilenhoehe, '', 0, 0, 'L' ) ;
	$i = 0 ;
	$zuabschlag = $_SESSION[ 'zuabschlag' ] ;
	$anz_zuabschlag = count( $zuabschlag ) ;
	while( $i < $anz_zuabschlag ) {
		$kurz	= $zuabschlag [ $i ][ 'kurz' ] ;
		$pdf->Cell( 5, $zeile, $kurz, 0, 0, 'C' ) ;
		$i++ ;
		}
	$pdf->Ln( );

	$wohnungen = $_SESSION[ 'wohnungen' ] ;
	$anz_wohnungen = count( $wohnungen ) ;
	$j = 0 ;
	while( $j < $anz_wohnungen ) {
		$wohngid = $wohnungen [ $j ][ 'wohngid' ] ;
		$wohnung = $wohnungen [ $j ][ 'wohnung' ] ;

		$pdf->Cell( 30, $zeile, $wohnung, 0, 0, 'L' ) ;

		$zuabschlag = $_SESSION[ 'zuabschlag' ] ;
		$anz_zuabschlag = count( $zuabschlag ) ;

		$k = 0 ;
		while( $k < $anz_zuabschlag ) {
			$kurz	= $zuabschlag [ $k ][ 'kurz' ] ;
			$abfrage = "SELECT * FROM $db_zuabwhng WHERE ZuAbWhng = $wohngid AND ZuAbKurz = '$kurz'" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;

			$row = mysql_fetch_object( $ergebnis ) ;
			if( $row ) {
				$onoff = 'X' ;
				}
			else {
				$onoff = '-' ;
				}

			$pdf->Cell( 5, $zeile, $onoff, 0, 0, 'C' ) ;
			$k++ ;
			}

		$pdf->Ln( ); 
		$j++ ;
		}
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$pdf->Ln( ) ;
?>
<!-- EOF -->