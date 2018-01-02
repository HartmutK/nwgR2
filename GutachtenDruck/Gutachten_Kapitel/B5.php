<?php
	$pos = $h2hoehe + $zeilenhoehe + $h2hoehe + $zeilenhoehe ;
	$frage = "SELECT * FROM $db_ugsink WHERE Gut8en = $gutachtenid ORDER BY Gruppe, Wann DESC, Was" ;
	$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $reply ) ) {
		$pos =$pos + $zeilenhoehe ;
		}  // while

	$pdf->SetFont( $schrift, 'B', $h2size ) ;

	if( $paragr6 ) {
		$ueberschrift = 'Grundlagen / Unterlagen' ;
		}
	else {
		$pdf->AddPage() ;
		$ueberschrift = 'B.5  Grundlagen / Unterlagen' ;
		$pdf->TOC_Entry( $ueberschrift, 1 ) ;
		}
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	
	$seitenpos = $pdf->GetY() ;
	if( $seitenhoehe - $seitenpos < $pos ) {
		$pdf->AddPage() ;
		}
	else {
//		$pdf->Ln();
		}

	$merkgrp = '' ;
	$fur = iconv('UTF-8', 'windows-1252', ' für/mit/an ' ) ;
	$bzw = iconv('UTF-8', 'windows-1252', ' mit der Akten bzw. Geschäftszahl ' ) ;

	$frage = "SELECT * FROM $db_ugsink WHERE Gut8en = $gutachtenid ORDER BY Gruppe, Wann DESC, Was" ;
	$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $reply ) ) {
		$gruppe	= $row->Gruppe ;
		$what		= $row->Was ;
		if( $paragr6 ) {
			switch( $gruppe ) {
				case 1 :
					$grp = 'Gesetzliche Grundlagen' ;
					break ;
				case 2 :
					$grp = 'Empfehlungen' ;
					break ;
				case 3 :
					$grp = 'Baubehördlicher Konsens' ;
					$bzw = ' mit der Plannummer ' ;
					break ;
				case 4 :
					$grp = 'Verträge, Vereinbarungen' ;
					break ;
				case 5 :
					$grp = 'Gutachten, Entscheide' ;
					break ;
				case 6 :
					$grp = 'Sonstige Unterlagen' ;
					break ;
				default:
					break ;
				}  // switch $gruppe
			} 
		else {
			switch( $gruppe ) {
				case 1 :
					$grp = 'B.5.1  Gesetzliche Grundlagen' ;
					break ;
				case 2 :
					$grp = 'B.5.2  Empfehlungen' ;
					break ;
				case 3 :
					$grp = 'B.5.3  Baubehördlicher Konsens' ;
					$bzw = ' mit der Plannummer ' ;
					break ;
				case 4 :
					$grp = 'B.5.4  Verträge, Vereinbarungen' ;
					break ;
				case 5 :
					$grp = 'B.5.5  Gutachten, Entscheide' ;
					break ;
				case 6 :
					$grp = 'B.5.6  Sonstige Unterlagen' ;
					break ;
				default:
					break ;
				}  // switch $gruppe
			} 

		if( $merkgrp != $gruppe ) {
			if( $merkgrp != '' ) {
				$pdf->Ln();
				}
			$pdf->SetFont( $schrift, 'B', $h3size ) ;
			$grp	= iconv('UTF-8', 'windows-1252', $grp ) ;
			$pdf->Cell( 0, $h3hoehe, $grp, 0, 1, 'L' ) ;
			$pdf->TOC_Entry( $grp, 2 ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			$merkgrp = $gruppe ;
			}

		if( $gruppe < 3 ) {
			$zeile	= $what ;
			}
		else {
			$when		= $row->Wann ;
			$when		= date( $dateform, strtotime( $when ) ) ;
			$who		= $row->Wer ;
			$where	= $row->Wo ;
			$akt		= $row->Akte ;
			$zeile	= $when . ' - ' . $what ;
			if( $who != '' ){ $zeile = $zeile . ' - ' . $who ; }
			if( $where != '' ){ $zeile = $zeile . $fur . $where ; }
			if( $akt != '' ){ $zeile = $zeile . $bzw . $akt ; }
			}
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $textbreite, $zeilenhoehe, $zeile );
		$posy = $pdf->GetY() + 2*$linemargin ;
		$pdf->setY( $posy); 
		}

	$frage1 = "SELECT NichtBefundet FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$reply1 = mysql_query( $frage1 )  OR die( "Error: $frage1 <br>". mysql_error() ) ;
	$row1 = mysql_fetch_object( $reply1 ) ;
	$nb	= $row1->NichtBefundet ;
	if( $nb != '' ) { 
		$pos = $h3hoehe + 3 * $zeilenhoehe ;
		$seitenpos = $pdf->GetY() ;
		if( $seitenhoehe - $seitenpos < $pos ) {
			$pdf->AddPage() ;
			}
		else {
			$pdf->Ln();
			}

		if( $paragr6 ) {
			$grp = 'Nicht befundet' ;
			}
		else {
			$grp = 'B.5.7  Nicht befundet' ;
			}
		$pdf->SetFont( $schrift, 'B', $h3size ) ;
		$pdf->Cell( 0, $h3hoehe, $grp, 0, 1, 'L' ) ;
		$pdf->TOC_Entry( $grp, 2 ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$pdf->MultiCell( 0, $zeilenhoehe, $nb );
		}
?>
<!-- EOF -->