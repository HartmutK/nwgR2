<?php
// -----------------------------------------------------------------------
// Collect DATA

unset( $table ) ;
$table = array( ) ;

$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

$erste = true ;

$abf1 = "SELECT * FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
while( $rw1 = mysql_fetch_object( $erg1 ) ) {
	$wohnid			= $rw1->WohnungID ;
	$wohnbez		= $rw1->Bezeichnung ;
	$widmung		= $rw1->Widmung ;
	$wohnlage		= $rw1->Lage ;
	$rwhng			= $rw1->Regelwohnung ;
	$wohnbeschr = $rw1->Beschreibung ;
	$wohnrw			= $rw1->Regelwohnung ;
	$wohnrnw		= $rw1->RNW ;
	$wohnrnwclc = $rw1->RNWcalc ;

	if( $wohnbeschr != '' ) {
		$we = $wohnbeschr ;
		}
	else {
		$we = $wohnbez;
		}
	$we = $we . ', ' . $widmung ;
	if( $wohnrw ) { $we = $we . ' (Regelwohnung)' ; }

	if( $wohnrnw == 0 ) {
		$wohnrnw = 1 ;
		}
	if( $erste ) {
		$posit = $h2size + 4 + $zeilenhoehe ;
		$erste = false ;
		}
	else {
		$posit = $h2size + 4 + 2*$zeilenhoehe ;
		}
	$table [ ] = array( 'was'=>'Wohn', 'pos'=>$posit, 'wohnid'=>$wohnid, 'raumid'=>0, 'id'=>0, 'bez'=>$we, 'lang'=>'', 'lage'=>$lage, 'einh'=>'', 'rnw'=>$wohnrnw, 'rnwcalc'=>$wohnrnwclc ) ;

	$abf2 = "SELECT * FROM $db_zuabwhng WHERE ZuAbWhng = $wohnid ORDER BY ZuAbKurz" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg2 ) ) {
		$kurz	= $row->ZuAbKurz ;
		$lang	= $row->ZuAbschlag ;
		$wert	= $row->Dflt ;
		$einh	= '%' ;

		$posit = $zeilenhoehe ;
		$table [ ] = array( 'was'=>'ZuAb', 'pos'=>$posit, 'wohnid'=>$wohnid, 'raumid'=>0, 'id'=>0, 'bez'=>$kurz, 'lang'=>$lang, 'lage'=>'', 'einh'=>$einh, 'rnw'=>$wert, 'rnwcalc'=>'' ) ;
		} // abf2
	$abf2 = "SELECT * FROM $db_raum WHERE InWohnungID = '$wohnid' ORDER BY Reihenfolge" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $erg2 ) ) {
		$raumid		= $rw2->RaumID ;
		$raumart 	= $rw2->Raumart ;
		$raumlage = $rw2->Lage ;
		$raumbez	= $rw2->Bezeichnung ;
		$bewert 	= $rw2->Bewertung ;
		$einh			= $rw2->BewertEinheit ;

		$posit = $zeilenhoehe ;
		$table [ ] = array( 'was'=>'Raum', 'pos'=>$posit, 'wohnid'=>$wohnid, 'raumid'=>$raumid, 'id'=>0, 'bez'=>$raumbez, 'lang'=>$raumart, 'lage'=>$raumlage, 'einh'=>$einh, 'rnw'=>$bewert, 'rnwcalc'=>'' ) ;
		} // abf2

	$newzus = true ;
	$newzub = true ;
	$abf2 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnid ORDER BY Zubehoer, GanzUnten, Bezeichnung" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $erg2 ) ) {
		$zid	= $rw2->ZuxxxID ;
		$bez	= $rw2->Bezeichnung ;
		$rpfl	= $rw2->Rechtspfleger ;
		$lage	= $rw2->Lage ;
		$zub	= $rw2->Zubehoer ;
		$wert = $rw2->Zuxxx ;
		$einh = $rw2->Einheit ;

		if( $rpfl != '-' AND $rpfl != '' ) {
			$bez = $rpfl ;
			}
		else {
			if( strpos( $bez, 'Terrasse' ) OR strpos( $bez, 'Balkon' )  ) {
				$bez = 'Terrasse/Balkon' ;
				}
			}
		$posit = $zeilenhoehe ;
		if( $zub ) {
			$was = 'Zub' ;
			if( $newzub ) {
				$posit = $posit + $zeilenhoehe ;
				$newzub = false ;
				}
			}
		else {
			$was = 'Zus' ;
			if( $newzus ) {
				$posit = $posit + $zeilenhoehe ;
				$newzus = false ;
				}
			}
		$table [ ] = array( 'was'=>$was, 'pos'=>$posit, 'wohnid'=>$wohnid, 'raumid'=>$raumid, 'id'=>$zid, 'bez'=>$bez, 'lang'=>'', 'lage'=>$lage, 'einh'=>$einh, 'rnw'=>$wert, 'rnwcalc'=>'' ) ;
		}  // while( $rw3
	} // abf1

//	END collect DATA
// -----------------------------------------------------------------------
// ------------- Print
// -------------------------------------------------------------------
//	Allg. Info
$ueberschrift =  'C.8  Ermittlung der Nutzwerte' ;

$head1=array( 'Bezeichnung', 'Lage', 'Bewertung', iconv('UTF-8', 'windows-1252', 'Nutzwert/m²' ) );
$w0=array( 20, 95, 30 ) ;
$w1=array( 90, 25, 30, 30 ) ;

$firstkfz = true ;

// -------------------------------------------------------------------
//	Start PRINT

if( $gutachtenid == 3 ) $pdf->AddPage() ;
$pdf->AddPage() ;
$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
$pdf->SetFont( $schrift, 'B', $h2size ) ;
$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
$pdf->TOC_Entry( $ueberschrift, 1 ) ;
$pdf->SetFont( $schrift, '', $schriftsize ) ;

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
	$lang			= $table [ $i ][ 'lang' ] ;
	$lage			= $table [ $i ][ 'lage' ] ;
	$einh			= $table [ $i ][ 'einh' ] ;
	$rnw			= $table [ $i ][ 'rnw' ] ;
	$rnwcalc	= $table [ $i ][ 'rnwcalc' ] ;

	$prtwohn = false ;
	$prtzuab = false ;
	$prtraumk = false ;
	$prtraum = false ;
	$prtzusk = false ;
	$prtzubk = false ;
	$prtzus = false ;
	$prtzub = false ;

	switch( $was ) {
		case 'Wohn' :
			$prtwohn = true ;
			switch( $nextwas ) {
				case 'ZuAb' :
					$prtraumk = false ;
					break;
				case 'Raum' :
					$prtraumk = true ;
					break;
				case 'Zus' :
					$prtraumk = true ;
					$prtzusk = true ;
					break ;
				case 'Zub' :
					$prtraumk = true ;
					$prtzubk = true ;
					break ;
				default:
					break ;
				}
			break ;
		case 'ZuAb' :
			$prtzuab = true ;
			switch( $nextwas ) {
				case 'ZuAb' :
					$prtraumk = false ;
					break;
				case 'Raum' :
					$prtraumk = true ;
					break;
				case 'Zus' :
					$prtraumk = true ;
					$prtzusk = true ;
					break ;
				case 'Zub' :
					$prtraumk = true ;
					$prtzubk = true ;
					break ;
				default:
					break ;
				}
			break ;
		case 'Raum' :
			$prtraum = true ;
			switch( $nextwas ) {
				case 'ZuAb' :
					break;
				case 'ZuAb' :
					break;
				case 'Zus' :
					$prtzusk = true ;
					break ;
				case 'Zub' :
					$prtzubk = true ;
					break ;
				default:
					break ;
				}
			break ;
		case 'Zus' :
			$prtzus = true ;
			if( $nextwas ==  'Zub' ) {
				$prtzubk = true ;
				}
			break ;
		case 'Zub' :
			$prtzub = true ;
			break ;
		default:
			break ;
		}

	if( $prtwohn ) {
		$prtwohn = false ;
		$pos = 0 ;
		$j = $i ;
		while( $nextwohn == $wohnid ) {
			$pos = $pos + $table [ $j ][ 'pos' ] ;
			$j++ ;
			$nextwohn	= $table [ $j ][ 'wohnid' ] ;
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
		if( $i > 0 ) {
			$pdf->Ln( ); 
			}

		$pdf->SetFont( $schrift, 'B', $h2size ) ;
		$pdf->SetFillColor(220, 220, 220) ;
		$pdf->Cell( $textbreite, $zeilenhoehe, $bez, 0, 1, 'L', 1 ) ;
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + 2 ;

		$pdf->setXY( $posx, $posy); 
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$pdf->Cell( $w1[0], $zeilenhoehe, 'Regelnutzwert', 0, 0, 'L' ) ;
		$rnwwohn = $rnw ;
		$prtrnw = number_format( round( $rnw, 4 ), 4, ',', '.' ) ;
		$pdf->Cell( $w1[1]+$w1[2]+$w1[3], $zeilenhoehe, $prtrnw, 0, 1, 'R' ) ;
		
		$gesabschlag = 0 ;
		}
	if( $prtzuab ) {
		$prtzuab = false ;
		$gesabschlag = $gesabschlag + $rnw ;

		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$pdf->Cell( $w0[0], $zeilenhoehe, $bez, 0, 0, 'L' ) ;

		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
		$pdf->setXY( $posx, $posy); 
		$pdf->MultiCell( $w0[1], $zeilenhoehe, $lang , '', 'L' ) ;
		$posx = $w0[ 0 ] + $w0[ 1 ] + $pagemargin_L;
		$posy = $pdf->GetY() - $zeilenhoehe ;
		$pdf->setXY( $posx, $posy ); 

		$num = number_format( round( $rnw, 2 ), 2, ',', '.' ) ;
		$pdf->Cell( $w0[2], $zeilenhoehe, $num . $einh, 0, 1, 'R' ) ;
		}
	if( $prtraumk ) {
		$prtraumk = false ;
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$pdf->Cell( $w1[0], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Nutzwert pro m²' ), 0, 0, 'L' ) ;
		$gesabschlag = $rnwwohn + $gesabschlag / 100 ;
		$nwm2 = number_format( round( $gesabschlag, 4 ), 4, ',', '.' ) ;
		$pdf->Cell( $w1[1]+$w1[2]+$w1[3], $zeilenhoehe, $nwm2, 0, 1, 'R' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy); 

		$pdf->Cell( $w1[ 0 ], $zeilenhoehe, $head1[ 0 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w1[ 1 ], $zeilenhoehe, $head1[ 1 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w1[ 2 ], $zeilenhoehe, $head1[ 2 ], 0, 0, 'R' ) ;
		$pdf->Cell( $w1[ 3 ], $zeilenhoehe, $head1[ 3 ], 0, 1, 'R' ) ;

		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy); 
		$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() + $linemargin ;
		$pdf->setXY( $posx, $posy);
		}

	if( $prtraum OR $prtzus OR $prtzub ) {
		$nwprom2 = $gesabschlag ;
		$c1 = substr( $einh, 0, 1 ) ;
		switch( $c1 ) {
			case '%' :
				$nwprom2 = $rnw * $gesabschlag / 100 ;
				$einh = iconv('UTF-8', 'windows-1252', ' % vom NW/m²' ) ;
//				$nwprom2 = $rnw * $gesabschlag / 100 ;
				break ;
			case 'N' :
				$einh = iconv('UTF-8', 'windows-1252', ' NW/m²' ) ;
				break ;
			case 'j' :
				$einh = iconv('UTF-8', 'windows-1252', ' je m²' ) ;
				break ;
			default:
				$einh = '' ;
				break ;
			}
		}
	if( $prtraum ) {
		$prtraum = false ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$pdf->Cell( $w1[0], $zeilenhoehe, $lang, 0, 0, 'L' ) ;
		$pdf->Cell( $w1[1], $zeilenhoehe, $lage, 0, 0, 'L' ) ;

//		$nwprom2 = $gesabschlag ;

		$num2 = number_format( round( $nwprom2, 3 ), 3, ',', '.' ) ;
		if( $rnw != 0 ) {
			$num1 = number_format( round( $rnw , 2 ), 2, ',', '.' ) . $einh ;
			}
		else {
			$num1 = '' ;
			}
		$pdf->Cell( $w1[2], $zeilenhoehe, $num1, 0, 0, 'R' ) ;
		$pdf->Cell( $w1[3], $zeilenhoehe, $num2, 0, 1, 'R' ) ;

		$abf6 = "UPDATE $db_raum SET Nutzwert = '$nwprom2' WHERE RaumID = '$raumid'" ;
		$erg6 = mysql_query( $abf6 ) OR die( "Error: $abf6 <br>". mysql_error() ) ;
		}

	if( $prtzusk ) {
		$prtzusk = false ;
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$pdf->Cell( $w1[ 0 ], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Zuschläge' ), 0, 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		}
	if( $prtzub ) {
		$prtzub = false ;

		$ab6 = "UPDATE $db_whngzuxxx SET NWpro_m2 = '$rnw' WHERE ZuxxxID = $id" ;
		$er6 = mysql_query( $ab6 ) OR die( "Error: $ab6 <br>". mysql_error() ) ;

		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
    $pdf->MultiCell( $w0[ 1 ]-5, $zeilenhoehe, $bez, '', 'L' ) ;
		$posx = $w1[ 0 ] + $w1[ 1 ] - 5 ;
		$posy = $pdf->GetY() - $zeilenhoehe ;
		$pdf->setXY( $posx, $posy ); 
		$pdf->Cell( $w1[1], $zeilenhoehe, $lage, 0, 0, 'L' ) ;
		$num1 = number_format( round( $rnw, 2 ), 2, ',', '.' ) . ' ' . $einh ;
		$num2 = number_format( round( $rnw, 3 ), 3, ',', '.' ) ;
		$pdf->Cell( $w1[2], $zeilenhoehe, $num1, 0, 0, 'R' ) ;
		$pdf->Cell( $w1[3], $zeilenhoehe, $num2, 0, 1, 'R' ) ;
		}
	if( $prtzus ) {
		$prtzus = false ;

		$ab6 = "UPDATE $db_whngzuxxx SET NWpro_m2 = '$nwprom2' WHERE ZuxxxID = $id" ;
		$er6 = mysql_query( $ab6 ) OR die( "Error: $ab6 <br>". mysql_error() ) ;

		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
    $pdf->MultiCell( $w0[ 1 ]-5, $zeilenhoehe, $bez, '', 'L' ) ;
		$posx = $w1[ 0 ] + $w1[ 1 ] - 5 ;
		$posy = $pdf->GetY() - $zeilenhoehe ;
		$pdf->setXY( $posx, $posy ); 
		$pdf->Cell( $w1[1], $zeilenhoehe, $lage, 0, 0, 'L' ) ;
		$num1 = number_format( round( $rnw, 2 ), 2, ',', '.' ) . ' ' . $einh ;
		$num2 = number_format( round( $nwprom2, 3 ), 3, ',', '.' ) ;
		$pdf->Cell( $w1[2], $zeilenhoehe, $num1, 0, 0, 'R' ) ;
		$pdf->Cell( $w1[3], $zeilenhoehe, $num2, 0, 1, 'R' ) ;
		}
	if( $prtzubk ) {
		$prtzubk = false ;
		$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
		$pdf->Cell( $w1[ 0 ], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Zubehör' ), 0, 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		}

	$i++ ;
	} // while( $cnt < $anz )
$pdf->Ln( ) ;
//	<!-- END Ermittlung der Nutzwerte -->
?>
<!-- EOF -->