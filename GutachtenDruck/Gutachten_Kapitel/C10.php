<?php
// Collect DATA

	unset( $table ) ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$abf2 = "SELECT SUM( Nutzwertanteil ) AS NutzwertGesamt FROM $db_wohnung WHERE InGutachten = $gutachtenid" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $erg2 ) ) {
		$nwgesamt	= $rw2->NutzwertGesamt ;
		}

	$abf2 = "SELECT * FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg2 ) ) {
		include( '../php/get_db_wohnung.php' ) ;
//		$we = $whngbezeichnung ;
//		if( $widmung != '' ) { $we = $we . ', ' . $widmung ; } 
//		if( $whnglage != '' ) { $we = $we . ', ' . $whnglage ; }
//		if( $regelwhng ) { $we = $we . ' (Regelwohnung)' ; }
		$table [ ] = array( 'was'=>'Wng', 'sp0'=>$whngbezeichnung , 'sp1'=>$whnglage, 'sp2'=>$nutzflaeche, 'sp3'=>$nutzwertanteil ) ;

		$abf3 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid AND Zubehoer ORDER BY Bezeichnung" ;
		$erg3 = mysql_query( $abf3 )  OR die( "Error: $abf3 <br>". mysql_error() ) ;
		while( $rw3 = mysql_fetch_object( $erg3 ) ) {
			$bezeich	= $rw3->Bezeichnung ;
			$rpfleg	= $rw3->Rechtspfleger ;
			if( $rpfleg != '' ) {
				$zub = $rpfleg ;
				}
			else {
				$zub = $bezeich ;
				}
			$table [ ] = array( 'was'=>'Zub', 'sp0'=>$zub, 'sp1'=>'', 'sp2'=>'', 'sp3'=>'' ) ;
			}
		}
//	END collect DATA
// -------------------------------------------------------------------
//	Allg. Info
	$pdf->AddPage() ;
	$ueberschrift =  'C.10  Zusammenfassung' ;

	$head1=array( 'WE-Objekt', 'Lage', iconv('UTF-8', 'windows-1252', 'm²' ), 'Anteile', 'Anteile x 2', '%Anteile' );
	$w1=array( 55, 35, 19, 22, 22, 22 ) ;

	$lh = 0.3 * $zeilenhoehe ;

// -------------------------------------------------------------------
//	Start PRINT
	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$sum3 = 0 ;
	$sum4 = 0 ;
	$prthead = true ;

	$cnt = 0 ;
	$anz = count( $table ) ;
	while( $cnt < $anz ) {

		if( $prthead ) {
			if( $cnt > 0 ) {
				$pdf->AddPage() ;
				$pdf->Ln( ) ; 
				}
			$pdf->SetFont( $schrift, 'B', $h2size ) ;
			$zh = $zeilenhoehe ;
			$pdf->Cell( $w1[0], $zh, $head1[ 0 ], '', 0, 'L' ) ;
			$pdf->Cell( $w1[1], $zh, $head1[ 1 ], '', 0, 'L' ) ;
			$pdf->Cell( $w1[2], $zh, $head1[ 2 ], '', 0, 'R' ) ;
			$pdf->Cell( $w1[3], $zh, $head1[ 3 ], '', 0, 'R' ) ;
			$pdf->Cell( $w1[4], $zh, $head1[ 4 ], '', 0, 'R' ) ;
			$pdf->Cell( $w1[5], $zh, $head1[ 5 ], '', 1, 'R' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			$prthead = false ;
			}

		$was = $table [ $cnt ][ 'was' ] ;
		$sp0 = $table [ $cnt ][ 'sp0' ] ;
		$sp1 = $table [ $cnt ][ 'sp1' ] ;
		$sp2 = $table [ $cnt ][ 'sp2' ] ;
		$sp3 = $table [ $cnt ][ 'sp3' ] ;
		if( $was == 'Zub' ) {
			$pdf->Cell( 15, $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Zubehör:' ), 0, 0, 'L' ) ;
			$zub = '' ;
			$zhl = $cnt ;
			while( $was == 'Zub' ) {
				$sp0 = $table [ $zhl ][ 'sp0' ] ;
				if( $zub != '' ) {
					$zub = $zub . ', ' . $sp0 ;
					}
				else {
					$zub = $sp0 ;
					}
				$zhl++ ;
				$was = $table [ $zhl ][ 'was' ] ;
				}
			$pdf->Cell( 0, $zeilenhoehe, $zub, '', 1, 'L' ) ;
			$cnt = $zhl - 1 ;
			$seitenpos = $pdf->GetY() ;
			$pos = 2 * $lh + 2 * $zeilenhoehe ;

			if( $seitenhoehe - $seitenpos < $pos ) {
				$prthead = true ;
				}
			}
		else {
			$pdf->Ln( $lh ) ; 
			$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
			$pdf->Ln( $lh ) ; 
			$pdf->Cell( $w1[0], $zeilenhoehe, $sp0, '', 0, 'L' ) ;

			$pdf->Cell( $w1[1], $zeilenhoehe, $sp1, '', 0, 'L' ) ;

			$num = number_format( round( $sp2, 2 ), 2, ',', '.' ) ;
			$pdf->Cell( $w1[2], $zeilenhoehe, $num, '', 0, 'R' ) ;

			$num = number_format( round( $sp3, 0 ), 0, ',', '.' ) . ' / ' . number_format( round( $nwgesamt, 0 ), 0, ',', '.' ) ;
			$pdf->Cell( $w1[3], $zeilenhoehe, $num, '', 0, 'R' ) ;

			$num = number_format( round( 2*$sp3, 0 ), 0, ',', '.' ) . ' / ' . number_format( round( 2*$nwgesamt, 0 ), 0, ',', '.' ) ;
			$pdf->Cell( $w1[4], $zeilenhoehe, $num, '', 0, 'R' ) ;

			if( $nwgesamt != 0 ) {
				$sp4 = $sp3*100/$nwgesamt ;
				}
			else {
				$sp4 = 0 ;
				}
			$sum3 = $sum3 + $sp3 ;
			$sum4 = $sum4 + $sp4 ;
			$num = number_format( round( $sp4, 3 ), 3, ',', '.' ) ;
			$pdf->Cell( $w1[5], $zeilenhoehe, $num, '', 1, 'R' ) ;
			}
		$cnt++ ;
		}

		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy); 
		$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy);

		$num = number_format( round( $sum3, 0 ), 0, ',', '.' ) . ' / ' . number_format( round( $nwgesamt, 0 ), 0, ',', '.' ) ;
		$pdf->Cell( $w1[0] + $w1[1] + $w1[2] + $w1[3], $zeilenhoehe, 'Summe: ' . $num, '', 0, 'R' ) ;

		$num = number_format( round( 2*$sum3, 0 ), 0, ',', '.' ) . ' / ' . number_format( round( 2*$nwgesamt, 0 ), 0, ',', '.' ) ;
		$pdf->Cell( $w1[4], $zeilenhoehe, $num, '', 0, 'R' ) ;

		$num = number_format( round( $sum4, 0 ), 0, ',', '.' ) . ' %' ;
		$pdf->Cell( $w1[5], $zeilenhoehe, $num, '', 1, 'R' ) ;
//		$pdf->Cell( $w1[5], $zeilenhoehe, '100 %', '', 1, 'R' ) ;

		$pdf->Ln( $lh ) ; 
		$pdf->Cell( $textbreite, 1, '', 'B', 1, 'L' ) ;
		$pdf->Cell( $textbreite, 1, '', '', 1, 'L' ) ;
		$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
		$pdf->Ln( ); 
//	End PRINT
?>
<!-- EOF -->
