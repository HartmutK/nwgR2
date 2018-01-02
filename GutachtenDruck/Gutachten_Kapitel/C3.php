<?php
// collect DATA
	unset( $table_c3 ) ;
	unset( $liste ) ;

	$table_c3 = array( ) ;
	$liste = array( ) ;

	$wohnungid = 0 ;

	$frage2 = "SELECT WohnungID FROM $db_wohnung WHERE InGutachten = $gutachtenid AND Regelwohnung" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb2 ) ) {
		$wohnungid = $row->WohnungID ;
		$table_c3 [ ] = array( 'was'=>'Wng', 'sp0'=>'', 'sp1'=>'', 'sp2'=>$wohnungid, 'sp3'=>0 ) ;
		} // Wohnung 

	$abfrage = "SELECT * FROM $db_raum WHERE InWohnungID = $wohnungid ORDER BY Raumart" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$rmart 		= $row->Raumart ;
		$rmlage 	= $row->Lage ;
		$flache		= $row->Flaeche ;
		$table_c3 [ ] = array( 'was'=>'Raum', 'sp0'=>$rmart, 'sp1'=>$rmlage, 'sp2'=>$flache, 'sp3'=>0 ) ;
		} // Räume 

	$frage3 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid ORDER BY Zubehoer, Bezeichnung" ;
	$ergeb3 = mysql_query( $frage3 )  OR die( "Error: $frage3 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb3 ) ) {
		$zubeh	= $row->Zubehoer ;
		$zubbez	= $row->Bezeichnung ;
		$zubxxx	= $row->Zuxxx ;
		$zubein	= $row->Einheit ;
		$flche	= $row->Flaeche ;
		$zublag	= $row->Lage ;
		$rpfleg	= $row->Rechtspfleger ;
		if( $rpfleg != '' ) {
			$zubbez = $rw3->Rechtspfleger ;
			}
		else {
			if( strpos( $zubbez, 'Terrasse' ) OR strpos( $zubbez, 'Balkon' )  ) {
				$zubbez = 'Terrasse/Balkon' ;
				}
			}
		if( $zublag	== '' ) { $zublag	= $whnglage  ; }
		$liste [ ] = array( 'zubeh'=>$zubeh, 'zubbez'=>$zubbez, 'wert'=>$zubxxx, 'einh'=>$zubein, 'flache'=>$flche, 'zublag'=>$zublag, 'rpfleg'=>$rpfleg ) ;
		} // while Zuschlag

// ------------------------------------------------------------------------
// PRINT
	$w0=array( 20, 35, 25, 35, 20, 25 ) ;

	$head=array( 'Objekt', 'Lage', iconv('UTF-8', 'windows-1252', 'Fläche' ) );
	$w=array( 80, 30,  $textbreite - 110 ) ;

	$pos = $h2hoehe ;
	if( $wohnungid == 0 ) {
		$pos = $pos +  2 * $zeilenhoehe ;
		}
	else {
		$cnt = count( $table_c3 ) + count( $liste ) ;
		$pos = $pos + $cnt * $zeilenhoehe + 4 ;
		}

	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
		$pdf->Ln( ) ; 
		}

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'C.3  Regelwohnung' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$geswohnung = 0 ;	

	if( $wohnungid == 0 ) {
		$pdf->Cell( 0, $h2hoehe, 'Regelwohnung nicht festgelegt.', 0, 1, 'L' ) ;
		}
	else {
		$cnt = 0 ;
		$anz = count( $table_c3 ) ;
		while( $cnt < $anz ) {
			$was = $table_c3 [ $cnt ][ 'was' ] ;
			$sp0 = $table_c3 [ $cnt ][ 'sp0' ] ;
			$sp1 = $table_c3 [ $cnt ][ 'sp1' ] ;
			$sp2 = $table_c3 [ $cnt ][ 'sp2' ] ;
			$sp3 = $table_c3 [ $cnt ][ 'sp3' ] ;

			$linie = $w[0] + $w[1] + $w[2] ;

			if( $was == 'Wng' ) {
				$frage2 = "SELECT * FROM $db_wohnung WHERE WohnungID = $sp2" ;
				$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb2 ) ) {
					include( '../php/get_db_wohnung.php' ) ;
					} // Wohnung 
	  	  $pdf->Cell( $w0[0], $zeilenhoehe, 'WE-Objekt:', '', 0, 'L' ) ;
	  	  $pdf->Cell( $w0[1], $zeilenhoehe, $whngbezeichnung, '', 0, 'L' ) ;
	  	  $pdf->Cell( $w0[2], $zeilenhoehe, 'Besichtigung:', '', 0, 'L' ) ;
				$besichtigung = date( $dateform, strtotime( $besichtigung ) ) ;
	  	  $pdf->Cell( $w0[3], $zeilenhoehe, $besichtigung, '', 1, 'L' ) ;
	  	  $pdf->Cell( $w0[0], $zeilenhoehe, 'Lage:', '', 0, 'L' ) ;
	  	  $pdf->Cell( $w0[1], $zeilenhoehe, $whnglage, '', 0, 'L' ) ;
				if( $whngzustand != '' ) {
		  	  $pdf->Cell( $w0[2], $zeilenhoehe, 'Zustand:', '', 0, 'L' ) ;
		  	  $pdf->Cell( $w0[3], $zeilenhoehe, $whngzustand, '', 1, 'L' ) ;
		  	  }
		  	else {
		  		$pdf->Ln( ) ;
		  		}
	  	  $pdf->Cell( $w0[0], $zeilenhoehe, 'Widmung:', '', 0, 'L' ) ;
	  	  $pdf->Cell( $w0[1], $zeilenhoehe, $widmung, '', 0, 'L' ) ;
	  	  if( $whngbeschreibung != '' ) {
			 	  $pdf->Cell( $w0[2], $zeilenhoehe, 'Anmerkung:', '', 0, 'L' ) ;
			 	  $pdf->Cell( $w0[3], $zeilenhoehe, $whngbeschreibung, '', 1, 'L' ) ;
		  	  }

	  	  $pdf->Cell( 0, $zeilenhoehe+2, '', '', 1, 'L' ) ;
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
				}
			else { // $was == 'Raum'
				$pdf->Cell( $w[0], $zeilenhoehe, $sp0, '', 0, 'L' ) ;
				$pdf->Cell( $w[1], $zeilenhoehe, $sp1, '', 0, 'L' ) ;
				$num = number_format( round( $sp2, 2 ), 2, ',', '.' ) ;
				$pdf->Cell( $w[2], $zeilenhoehe, $num . iconv('UTF-8', 'windows-1252', ' m²' ),  '', 1, 'R' ) ;
				$geswohnung = $geswohnung + $sp2 ;	
				}
			$cnt++ ;
			} // while( $cnt < $anz )

		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$num = number_format( round( $geswohnung, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $linie, $zeilenhoehe, 'Gesamt: ' . $num . iconv('UTF-8', 'windows-1252', ' m²' ),  '', 1, 'R' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		}

	$firstzub = true ;
	$i = 0 ;
	$anz = count( $liste ) ;
	$merk_zubeh = '' ;
	while( $i < $anz ) {
		$zubeh	= $liste [ $i ][ 'zubeh' ] ;
		$zubbez	= $liste [ $i ][ 'zubbez' ] ;
		$wert		= $liste [ $i ][ 'wert' ] ;
		$einh		= $liste [ $i ][ 'einh' ] ;
		$flache	= $liste [ $i ][ 'flache' ] ;
		$zublag	= $liste [ $i ][ 'zublag' ] ;
		$rpfleg	= $liste [ $i ][ 'rpfleg' ] ;
		if( $rpfleg != '' ) {
			$zubbez = $rpfleg ;
			}
		if( !$zubeh ) {
			$heading = iconv('UTF-8', 'windows-1252', 'Zuschläge' ) ;
			}
		else {
			$heading = iconv('UTF-8', 'windows-1252', 'Zubehör' ) ;
			}
		if( $merk_zubeh != $zubeh ) {
			if( $firstzub ) {
				$lm = -3 ;
				$firstzub = false ;
				}
			else {
				$lm = $linemargin + 1 ;
				}
			$posx = $pdf->GetX() ;
			$posy = $pdf->GetY() + $lm ;
			$pdf->setXY( $posx, $posy);
			$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
			$pdf->Cell( 50, $zeilenhoehe, $heading , '', 1, 'L' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			}
		$merk_zubeh = $zubeh ;

		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
		$pdf->setXY( $posx, $posy); 
    $pdf->MultiCell( 75, $zeilenhoehe, $zubbez, '', 'L' ) ;
		$posx = 80 + $pagemargin_L;
		$posy = $pdf->GetY() - $zeilenhoehe ;
		$pdf->setXY( $posx, $posy ); 

		$pdf->Cell( 15, $zeilenhoehe, $zublag, '', 0, 'L' ) ;
		$wert	= number_format( round( $flache, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $w[2]+14, $zeilenhoehe, $wert . iconv('UTF-8', 'windows-1252', ' m²' ),  '', 1, 'R' ) ;

		$i++ ;
		}
// END C.3 Regelwohnung
?>
<!-- EOF -->