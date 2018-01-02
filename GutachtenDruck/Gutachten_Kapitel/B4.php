<?php
// -----------------------------------------------------------------------
// Collect DATA

unset( $table ) ;

$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

$abf1 = "SELECT WohnungID, Bezeichnung, Widmung, Regelwohnung, Nutzflaeche FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
while( $rw1 = mysql_fetch_object( $erg1 ) ) {
	$wohnungid	= $rw1->WohnungID ;
	$whngbez		= $rw1->Bezeichnung ;
	$widmung		= $rw1->Widmung ;
	$rwhng			= $rw1->Regelwohnung ;
	$efl				= $rw1->Nutzflaeche ;
	$posit			= $h2size + 2 + $zeilenhoehe + 2 * $linemargin ;
	$table [ ]	= array( 'was'=>'Wohn', 'pos'=>$posit, 'wohnid'=>$wohnungid, 'raumid'=>0, 'id'=>0, 'bez'=>$whngbez, 'anmerk'=>$widmung, 'lage'=>$rwhng, 'formel'=>'', 'berechnung'=>'', 'efl'=>$efl ) ;

	$flwohn = 0 ;
	$abf2 = "SELECT RaumID, Raumart, Anmerkung, Lage, Flaeche FROM $db_raum WHERE InWohnungID = $wohnungid ORDER BY Reihenfolge" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $erg2 ) ) {
		$raumid		= $rw2->RaumID ;
		$raumbez	= $rw2->Raumart ;
		$anmerk		= $rw2->Anmerkung ;
		$raumlage	= $rw2->Lage ;
		$raumflch	= $rw2->Flaeche ;
		$table [ ]	= array( 'was'=>'Raum', 'pos'=>$zeilenhoehe, 'wohnid'=>$wohnungid, 'raumid'=>$raumid, 'id'=>0, 'bez'=>$raumbez, 'anmerk'=>$anmerk, 'lage'=>$raumlage, 'formel'=>'', 'berechnung'=>'', 'efl'=>$raumflch) ;

		$flraum = 0 ;
		$abf3 = "SELECT MassID, Form, Flaeche FROM $db_abmessung WHERE vonRaumID = $raumid" ;
		$erg3 = mysql_query( $abf3 )  OR die( "Error: $abf3 <br>". mysql_error() ) ;
		while( $rw3 = mysql_fetch_object( $erg3 ) ) {
			$mid	= $rw3->MassID ;
			$form	= $rw3->Form ;
			$flch	= $rw3->Flaeche ;
			$flraum = $flraum + $flch ;
			$flwohn = $flwohn + $flch ;
			$abf4 = "SELECT Formel, ZuRechnen FROM $db_form WHERE Form = $form" ;
			$erg4 = mysql_query( $abf4 )  OR die( "Error: $abf4 <br>". mysql_error() ) ;
			$rw4 = mysql_fetch_object( $erg4 ) ;
			if( $rw4 ) {
				$formel	= $rw4->Formel ;
				$rechne	= $rw4->ZuRechnen ;
				}
			else {
				$formel	= '' ;
				$rechne	= '' ;
				}
//			$table [ ]	= array( 'was'=>'Mass', 'pos'=>$zeilenhoehe, 'wohnid'=>$wohnungid, 'raumid'=>$raumid, 'id'=>$mid, 'bez'=>'', 'anmerk'=>'', 'lage'=>'', 'formel'=>$formel, 'berechnung'=>$rechne, 'efl'=>$flch ) ;
			}  // while( $rw3
		}  // while( $rw2
	$newzus = true ;
	$newzub = true ;
	$abf3 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid ORDER BY Zubehoer, GanzUnten, Bezeichnung" ;
	$erg3 = mysql_query( $abf3 )  OR die( "Error: $abf3 <br>". mysql_error() ) ;
	while( $rw3 = mysql_fetch_object( $erg3 ) ) {
		$zid	= $rw3->ZuxxxID ;
		$bez	= $rw3->Bezeichnung ;
		$rpfl	= $rw3->Rechtspfleger ;
		if( $rpfl != '' ) {
			$bez = $rw3->Rechtspfleger ;
			}
		else {
			if( strpos( $bez, 'Terrasse' ) OR strpos( $bez, 'Balkon' )  ) {
				$bez = 'Terrasse/Balkon' ;
				}
			}
		$lage	= $rw3->Lage ;
		$efl	= $rw3->Flaeche ;
		$zub	= $rw3->Zubehoer ;
		$pos	= $zeilenhoehe ;
		if( $zub ) {
			$was = 'Zub' ;
			if( $newzub ) {
				$pos = $pos + $zeilenhoehe ;
				$newzub = false ;
				}
			}
		else {
			$was = 'Zus' ;
			if( $newzus ) {
				$pos = $pos + $zeilenhoehe ;
				$newzus = false ;
				}
			}
		$table [ ]	= array( 'was'=>$was, 'pos'=>$pos, 'wohnid'=>$wohnungid, 'raumid'=>$raumid, 'id'=>$zid, 'bez'=>$bez, 'anmerk'=>$rpfl, 'lage'=>$lage, 'formel'=>'', 'berechnung'=>'', 'efl'=>$efl ) ;
		}  // while( $rw3
	}  // while( $rw1

// -----------------------------------------------------------------------
// ------------- Print

$wohnflaeche = 0 ;
$raumflaeche = 0 ;

$head1 = array( iconv('UTF-8', 'windows-1252', 'Räume' ), 'Anmerkungen', 'Lage', 'Formel', 'Berechnung', iconv('UTF-8', 'windows-1252', 'EFL in m²' ), iconv('UTF-8', 'windows-1252', 'GFL in m²' ) );
$w=array( 40, 30, 15, 20, 35, 17, 18 ) ;
$summen = $w[0] + $w[1] + $w[2] + $w[3] + $w[ 4 ] + $w[ 5 ] ;

$firstkfz = true ;

$i = 0 ;
$anz = count( $table ) ;
while( $i < $anz ) {
	$was			= $table [ $i ][ 'was' ] ;
	$nextwas	= $table [ $i+1 ][ 'was' ] ;
	$wo				= $table [ $i ][ 'pos' ] ;
	$wohnid		= $table [ $i ][ 'wohnid' ] ;
	$nextwohn = $wohnid ;
	$raumid		= $table [ $i ][ 'raumid' ] ;
	$id				= $table [ $i ][ 'id' ] ;
	$bez			= $table [ $i ][ 'bez' ] ;
	$anmerk		= $table [ $i ][ 'anmerk' ] ;
	$lage			= $table [ $i ][ 'lage' ] ;
	$formel		= $table [ $i ][ 'formel' ] ;
	$rechnung	= $table [ $i ][ 'berechnung' ] ;
	$efl			= $table [ $i ][ 'efl' ] ;

	if( $was == 'Wohn' ) {
		$pos = 0 ;
		$j = $i ;
		while( $nextwohn == $wohnid ) {
			$pos = $pos + $table [ $j ][ 'pos' ] ;
			$j++ ;
			$nextwohn	= $table [ $j ][ 'wohnid' ] ;
			}

		if( $i == 0 ) {
			$pdf->AddPage() ;
			$ueberschrift = iconv('UTF-8', 'windows-1252', 'B.4  Nutzflächenberechnung' ) ;
			$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
			$pdf->SetFont( $schrift, 'B', $h2size ) ;
			$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
			$pdf->TOC_Entry( $ueberschrift, 1 ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			}

		if( substr( $bez, 0, 3 ) == 'KFZ' AND $firstkfz ) {
			$pdf->AddPage() ;
			$firstkfz = false ;
			}

		$seitenpos = $pdf->GetY() ;
		if( $seitenhoehe - $seitenpos < $pos ) {
			$pdf->AddPage() ;
			$pos = $h2hoehe + $zeilenhoehe ; // Leerzeile ab 2. Wohnung
			}

		$weo_txt = $bez ;
		if( $widmung != '' ) { $weo_txt = $weo_txt . ', ' . $anmerk ;}
		if( $lage == 1 ) { $weo_txt = $weo_txt . ' (Regelwohnung)' ;}

		if( $i != 0 ) {
			$pdf->Ln( ); 
			}
		$pdf->SetFont( $schrift, 'B', $h2size ) ;
		$pdf->SetFillColor(220, 220, 220) ;
		$pdf->Cell( $textbreite, $zeilenhoehe, $weo_txt, 0, 1, 'L', 1 ) ;
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + 2 ;
		$pdf->setXY( $posx, $posy); 
		$pdf->Cell( $w[ 0 ], $zeilenhoehe, $head1[ 0 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, $head1[ 1 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 2 ], $zeilenhoehe, $head1[ 2 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 3 ], $zeilenhoehe, $head1[ 3 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 4 ], $zeilenhoehe, $head1[ 4 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 5 ], $zeilenhoehe, $head1[ 5 ], 0, 0, 'R' ) ;
		$pdf->Cell( $w[ 6 ], $zeilenhoehe, $head1[ 6 ], 0, 1, 'R' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy); 
		$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy);
		$newzus = true ;
		$newzub = true ;
		}
	elseif( $was == 'Raum' ) {
		$pdf->Cell( $w[ 0 ], $zeilenhoehe, $bez, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, $anmerk, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 2 ], $zeilenhoehe, $lage, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 3 ], $zeilenhoehe, $formel, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 4 ], $zeilenhoehe, $rechnung, 0, 0, 'L' ) ;
		$hlp = number_format( round( $efl, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $w[ 5 ], $zeilenhoehe, $hlp, 0, 0, 'R' ) ;
		$wohnflaeche = $wohnflaeche + $efl ;
		if( $nextwas != 'Raum' ) {
			$hlp = number_format( round( $wohnflaeche, 2 ), 2, ',', '.' ) ;
			}
		else {
			$hlp = '' ;
			}
		$pdf->Cell( $w[ 6 ], $zeilenhoehe, $hlp, 0, 1, 'R' ) ;
		}
	elseif( $was == 'Zub' ) {
		if( $newzub ) {
			$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Zubehör' ), 0, 1, 'L' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			$newzub = false ;
			}
		$pdf->Cell( $w[ 0 ], $zeilenhoehe, $bez, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, '', 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 2 ], $zeilenhoehe, $lage, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 3 ], $zeilenhoehe, '', 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 4 ], $zeilenhoehe, '', 0, 0, 'L' ) ;
		$hlp = number_format( round( $efl, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $w[ 5 ], $zeilenhoehe, $hlp, 0, 0, 'R' ) ;
		$pdf->Cell( $w[ 6 ], $zeilenhoehe, $hlp, 0, 1, 'R' ) ;
		}
	elseif( $was == 'Zus' ) {
		if( $newzus ) {
			$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Zuschläge' ), 0, 1, 'L' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			$newzus = false ;
			}
		$pdf->Cell( $w[ 0 ], $zeilenhoehe, $bez, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, '', 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 2 ], $zeilenhoehe, $lage, 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 3 ], $zeilenhoehe, '', 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 4 ], $zeilenhoehe, '', 0, 0, 'L' ) ;
		$hlp = number_format( round( $efl, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $w[ 5 ], $zeilenhoehe, $hlp, 0, 0, 'R' ) ;
		$pdf->Cell( $w[ 6 ], $zeilenhoehe, $hlp, 0, 1, 'R' ) ;
		}
	elseif( $was == 'Mass' ) {
		$raumflaeche = $raumflaeche + $efl ;
		}
	else {
		}

	if( $nextwas == 'Wohn' OR $i == $anz - 1 ) {
		$hlp = number_format( round( $wohnflaeche, 2 ), 2, ',', '.' ) ;
		$nfl = iconv('UTF-8', 'windows-1252', 'Nutzfläche: ' . $hlp . ' m²' ) ;
		$pdf->SetFont( $schrift, 'B', $h2size ) ;
		$pdf->Cell( 0, $zeilenhoehe, $nfl, 0, 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$wohnflaeche = 0 ;
		}
	
	$i++ ;
	}  // while( $i für Druck

// ------------- END Nutzflächenberechnung
?>
<!-- EOF -->