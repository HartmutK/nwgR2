<?php
// -------------------------------------------------------------------
// Collect DATA

	unset( $table ) ;

	$nwgesamt = 0 ;
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$erste = true ;

	$abf2 = "SELECT * FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg2 ) ) {
		include( '../php/get_db_wohnung.php' ) ;
		$nutzflaeche = 0 ;
		$abf5 = "SELECT * FROM $db_raum WHERE InWohnungID = '$wohnungid'" ;
		$erg5 = mysql_query( $abf5 )  OR die( "Error: $abf5 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $erg5 ) ) {
			include( '../php/get_db_raeume.php' ) ;
			$nutzflaeche = $nutzflaeche + $flaeche ;
			$nweinzel = $flaeche * $nutzwert ;
			$abf6 = "UPDATE $db_raum SET Einzelnutzwert = '$nweinzel' WHERE RaumID = '$raumid'" ;
			$erg6 = mysql_query( $abf6 ) OR die( "Error: $abf6 <br>". mysql_error() ) ;
			}
		}

	$abf2 = "SELECT * FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg2 ) ) {
		include( '../php/get_db_wohnung.php' ) ;
		$we = $whngbezeichnung ;
		if( $widmung != '' ) { $we = $we . ', ' . $widmung ; } 
		if( $regelwhng ) { $we = $we . ' (Regelwohnung)' ; }
		if( $erste ) {
			$erste = false ;
			}
		$posit = $h2hoehe + 2 * $linemargin + $zeilenhoehe ;
		$table [ ] = array( 'was'=>'Wng', 'pos'=>$posit, 'sp0'=>$we, 'sp1'=>$whnglage, 'sp2'=>0, 'sp3'=>0, 'sp4'=>0, 'sp5'=>0, 'idw'=>$wohnungid, 'idr'=>0 ) ;

		$nwwohnung = 0 ;
		$abf5 = "SELECT * FROM $db_raum WHERE InWohnungID = '$wohnungid' ORDER BY Reihenfolge" ;
		$erg5 = mysql_query( $abf5 )  OR die( "Error: $abf5 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $erg5 ) ) {
			include( '../php/get_db_raeume.php' ) ;
			$nwwohnung = $nwwohnung + $nweinzel ;
			$nwgesamt = $nwgesamt + $nweinzel ;
			$summ = round( $nwwohnung, 0 ) ;
			$posit = $zeilenhoehe ;
			$table [ ] = array( 'was'=>'Raum', 'pos'=>$posit, 'sp0'=>$raumart, 'sp1'=>$raumlage, 'sp2'=>$flaeche, 'sp3'=>$nutzwert, 'sp4'=>$nweinzel, 'sp5'=>$summ, 
															'idw'=>$wohnungid, 'idr'=>$raumid ) ;
			}

		$neukopf = true ;
		$summ = 0 ;
		$abf5 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid AND !Zubehoer ORDER BY Bezeichnung" ;
		$erg5 = mysql_query( $abf5 )  OR die( "Error: $abf5 <br>". mysql_error() ) ;
		while( $rw5 = mysql_fetch_object( $erg5 ) ) {
			if( $neukopf ) {
				$posit = $zeilenhoehe ;
				$table [ ] = array( 'was'=>'Zus', 'pos'=>$posit, 'sp0'=>'', 'sp1'=>'', 'sp2'=>0, 'sp3'=>0, 'sp4'=>0, 'sp5'=>0, 'idw'=>$wohnungid, 'idr'=>$raumid ) ;
				$neukopf = false ;
				}
			$bez	= $rw5->Bezeichnung ;
			if( strpos( $bez, 'Terrasse' ) OR strpos( $bez, 'Balkon' )  ) {
				$bez = 'Terrasse/Balkon' ;
				}
			$lage = $rw5->Lage ;
			$flaeche = $rw5->Flaeche ;
			$nwm2 = $rw5->NWpro_m2 ;
			$enw = $flaeche * $nwm2 ;
			$summ = round( $enw, 0 ) ;
			if( $summ == 0 ) { $summ = 1 ; }
			$nwgesamt = $nwgesamt + $summ ;
			$posit = $zeilenhoehe ;
			$table [ ] = array( 'was'=>'Raum', 'pos'=>$posit, 'sp0'=>$bez, 'sp1'=>$lage, 'sp2'=>$flaeche, 'sp3'=>$nwm2, 'sp4'=>$enw, 'sp5'=>$summ, 'idw'=>$wohnungid, 'idr'=>$raumid ) ;
			}

		$neukopf = true ;
		$summ = 0 ;
		$abf5 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid AND Zubehoer ORDER BY GanzUnten, Bezeichnung" ;
		$erg5 = mysql_query( $abf5 )  OR die( "Error: $abf5 <br>". mysql_error() ) ;
		while( $rw5 = mysql_fetch_object( $erg5 ) ) {
			if( $neukopf ) {
				$posit = $zeilenhoehe ;
				$table [ ] = array( 'was'=>'Zub', 'pos'=>$posit, 'sp0'=>'', 'sp1'=>'', 'sp2'=>0, 'sp3'=>0, 'sp4'=>0, 'sp5'=>0, 'idw'=>$wohnungid, 'idr'=>$raumid ) ;
				$neukopf = false ;
				}
			$recht = $rw5->Rechtspfleger ;
			if( $recht != '' ) {
				$bez	= $recht ;
				}
			else {
				$bez	= $rw5->Bezeichnung ;
				}
			$lage = $rw5->Lage ;
			$flaeche = $rw5->Flaeche ;
			$nwm2 = $rw5->NWpro_m2 ;
			$enw = $flaeche * $nwm2 ;
			$summ = round( $enw, 0 ) ;
			if( $summ == 0 ) { $summ = 1 ; }
			$nwgesamt = $nwgesamt + $summ ;
			$posit = $zeilenhoehe ;
			$table [ ] = array( 'was'=>'Raum', 'pos'=>$posit, 'sp0'=>$bez, 'sp1'=>$lage, 'sp2'=>$flaeche, 'sp3'=>$nwm2, 'sp4'=>$enw, 'sp5'=>$summ, 'idw'=>$wohnungid, 'idr'=>$raumid ) ;
			}

		$posit = $zeilenhoehe ;
		$nwg = round( $nwgesamt, 0 ) ;
		$table [ ] = array( 'was'=>'Anteil', 'pos'=>$posit, 'sp0'=>'', 'sp1'=>'', 'sp2'=>$nwwohnung, 'sp3'=>$nwgesamt, 'sp4'=>0, 'sp5'=>0, 'idw'=>$wohnungid, 'idr'=>$raumid ) ;
		}
//	END collect DATA
// -------------------------------------------------------------------
//	Start PRINT

	$abf2 = "SELECT SUM( Nutzwertanteil ) AS NutzwertGesamt FROM $db_wohnung WHERE InGutachten = $gutachtenid" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $erg2 ) ) {
		$nwtotal	= $rw2->NutzwertGesamt ;
		}

	$ueberschrift =  'C.9  Nutzwertberechnung' ;

	$head1=array( 'Bezeichnung', 'Lage', iconv('UTF-8', 'windows-1252', 'm²' ), iconv('UTF-8', 'windows-1252', 'NW/m²' ), 'ENW', 'gerundet' );
	$w1=array( 60, 35, 20, 20, 20, 20 ) ;

// -------------------------------------------------------------------
	$cnt = 0 ;
	$anz = count( $table ) ;
	while( $cnt < 0 ) {
		$was = $table [ $cnt ][ 'was' ] ;
		$sp0 = $table [ $cnt ][ 'sp0' ] ;
		$sp1 = $table [ $cnt ][ 'sp1' ] ;
		$sp2 = $table [ $cnt ][ 'sp2' ] ;
		$sp3 = $table [ $cnt ][ 'sp3' ] ;
		$sp4 = $table [ $cnt ][ 'sp4' ] ;
		$sp5 = $table [ $cnt ][ 'sp5' ] ;

		$pdf->Cell( 30, $zeilenhoehe, $was, '', 0, 'L' ) ;
		$pdf->Cell( 30, $zeilenhoehe, $sp0, '', 0, 'L' ) ;
		$pdf->Cell( $w1[1], $zeilenhoehe, $sp1, '', 0, 'L' ) ;
		$pdf->Cell( $w1[2], $zeilenhoehe, $sp2, '', 0, 'R' ) ;
		$pdf->Cell( $w1[3], $zeilenhoehe, $sp3, '', 0, 'R' ) ;
		$pdf->Cell( $w1[4], $zeilenhoehe, $sp4, '', 0, 'R' ) ;
		$pdf->Cell( $w1[5], $zeilenhoehe, $sp5, '', 1, 'R' ) ;

		$cnt++ ;
		}

	$firstkfz = true ;

	$cnt = 0 ;
	$anz = count( $table ) ;

	while( $cnt < $anz ) {
		$was = $table [ $cnt ][ 'was' ] ;
		if( $cnt != $anz-1 ) {
			$nextwas = $table [ $cnt+1 ][ 'was' ] ;
			}
		else {
			$nextwas = $table [ $cnt ][ 'was' ] ;
			}
		$wo	 = $table [ $cnt ][ 'pos' ] ;
		$sp0 = $table [ $cnt ][ 'sp0' ] ;
		$sp1 = $table [ $cnt ][ 'sp1' ] ;
		$sp2 = $table [ $cnt ][ 'sp2' ] ;
		$sp3 = $table [ $cnt ][ 'sp3' ] ;
		$sp4 = $table [ $cnt ][ 'sp4' ] ;
		$sp5 = $table [ $cnt ][ 'sp5' ] ;
		$idw = $table [ $cnt ][ 'idw' ] ;
		$nextwohn = $idw ;
		$lastwas = $was ;

		switch( $was ) {
			case 'Wng' :
				$pos = 0 ;
				$j = $cnt ;
				while( $nextwohn == $idw ) {
					$pos = $pos + $table [ $j ][ 'pos' ] ;
					$j++ ;
					$nextwohn	= $table [ $j ][ 'idw' ] ;
					}
				if( substr( $sp0, 0, 3 ) == 'KFZ' AND $firstkfz ) {
					$pdf->AddPage() ;
					$firstkfz = false ;
					}
				$seitenpos = $pdf->GetY() ;
				if( $seitenhoehe - $seitenpos < $pos ) {
					$pdf->AddPage() ;
					$pos = $h2hoehe + $zeilenhoehe ; // Leerzeile ab 2. Wohnung
					}
				if( $cnt > 0 ) {
					$pdf->Ln( ); 
					}
				else {
					if( $paragr9 ) $pdf->AddPage() ;
					$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
					$pdf->SetFont( $schrift, 'B', $h2size ) ;
					$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
					$pdf->TOC_Entry( $ueberschrift, 1 ) ;
					$pdf->SetFont( $schrift, '', $schriftsize ) ;
					}
				$pdf->SetFont( $schrift, 'B', $h2size ) ;
				$pdf->SetFillColor(220, 220, 220) ;
				$pdf->Cell( $textbreite, $zeilenhoehe, $sp0, 0, 1, 'L', 1 ) ;
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( $textbreite, 2, '', 0, 1, 'L' ) ;
				$zh = $zeilenhoehe ;
				$pdf->Cell( $w1[0], $zh, $head1[ 0 ], '', 0, 'L' ) ;
				$pdf->Cell( $w1[1], $zh, $head1[ 1 ], '', 0, 'L' ) ;
				$pdf->Cell( $w1[2], $zh, $head1[ 2 ], '', 0, 'R' ) ;
				$pdf->Cell( $w1[3], $zh, $head1[ 3 ], '', 0, 'R' ) ;
				$pdf->Cell( $w1[4], $zh, $head1[ 4 ], '', 0, 'R' ) ;
				$pdf->Cell( $w1[5], $zh, $head1[ 5 ], '', 1, 'R' ) ;
				$posx = $pdf->GetX() ;
				$posy = $pdf->GetY() + $linemargin ;
				$pdf->setXY( $posx, $posy); 
				$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$posx = $pdf->GetX() ;
				$posy = $pdf->GetY() + $linemargin ;
				$pdf->setXY( $posx, $posy);
				$nwwng = 0 ;
				$anteil = 0 ;
				$zuszub = false ;
				break ;  // Wng
			case 'Raum' :
				$posx = $pdf->GetX() ;
				$posy = $pdf->GetY() ;
				$pdf->setXY( $posx, $posy); 
		    $pdf->MultiCell( $w1[ 0 ]-5, $zeilenhoehe, $sp0, '', 'L' ) ;
				$posx = $w1[ 0 ]+ $pagemargin_L;
				$posy = $pdf->GetY() - $zeilenhoehe ;
				$pdf->setXY( $posx, $posy ); 
				$pdf->Cell( $w1[1], $zeilenhoehe, $sp1, '', 0, 'L' ) ;
				$num = number_format( round( $sp2, 2 ), 2, ',', '.' ) ;
				$pdf->Cell( $w1[2], $zeilenhoehe, $num, '', 0, 'R' ) ;
				$num = number_format( round( $sp3, 3 ), 3, ',', '.' ) ;
				$pdf->Cell( $w1[3], $zeilenhoehe, $num, '', 0, 'R' ) ;
				$num = number_format( round( $sp4, 2 ), 2, ',', '.' ) ;
				$pdf->Cell( $w1[4], $zeilenhoehe, $num, '', 0, 'R' ) ;
		
				if( $zuszub OR $nextwas != 'Raum' ) {
					$num = number_format( round( $sp5, 0 ), 0, ',', '.' ) ;
					$pdf->Cell( $w1[5], $zeilenhoehe, $num, '', 1, 'R' ) ;
					$nwwng = $nwwng + $sp5 ;
					}
				else {
					$pdf->Ln( ); 
					}
				break ;  // Raum
			case 'Zus' :
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( 0, $zeilenhoehe+2, iconv('UTF-8', 'windows-1252', 'Zuschläge' ), 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$zuszub = true ;
				break ;  // Zus
			case 'Zub' :
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( 0, $zeilenhoehe+2, iconv('UTF-8', 'windows-1252', 'Zubehör' ), 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$zuszub = true ;
				break ;  // Zub
			case 'Anteil' :
				$anteil = round( $nwwng, 0 ) ;
//				$gesamt = round( $nwgesamt, 0 ) ;
				$gesamt = round( $nwtotal, 0 ) ;
				$anteil2 = 2*$anteil ;
				$gesamt2 = 2*$gesamt ;
			
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$seitenpos = $pdf->GetY() ;

				$teil = number_format( $anteil, 0, ',', '.' ) ;

				$pdf->Cell( $textbreite, $zeilenhoehe, 'Mindestanteil: ' . $teil . ' von ' . $gesamt . ' (' . $anteil2 . ' von ' . $gesamt2 . ')', '', 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;

				if( substr( $sp0, 0, 3 ) == 'KFZ' ) $anteil = $sp5 ;

				$abf7 = "UPDATE $db_wohnung SET Nutzflaeche = '$sp2', Nutzwertanteil = '$anteil' WHERE WohnungID = $idw" ;
				$erg7 = mysql_query( $abf7 ) OR die( "Error: $abf7 <br>". mysql_error() ) ;
				break ;  // Anteil
			default:
				break ;
				}

			$cnt++ ;
			}
//	End PRINT
?>
<!-- EOF -->