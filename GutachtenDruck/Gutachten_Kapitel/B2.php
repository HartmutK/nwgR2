<?php
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abf = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		if( $abgeschl ) {
			$titeldat = $ausgestellt ;
			}
		else {
			$titeldat = date( 'd.m.Y' ) ;
			}
		}
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'B.2  Lage, Objekt- und Ausstattungsbeschreibung' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, 'B', $h3size ) ;
	$ueberschrift = 'B.2.1  Lage' ;
	$pdf->Cell( 0, $h3hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 2 ) ;

	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Lageplan" ;
	$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		$pfad = $row->DokumentenPfad . $row->Dokument ;

		if (file_exists( $pfad )) {
			$pixw = 174 ;
			list( $width, $height ) = getimagesize( $pfad ) ;
			if( $width > $pixw ) {
				$proz = $pixw * 100 / $width ;
				$neww = round( $width * $proz / 100, 2 );
				$newh = round( $height * $proz / 100, 2 );
				}
			$posx = $pdf->GetX() + 1 ;
			$posy = $pdf->GetY() ;
			$pdf->Image( $pfad, $posx, $posy, $neww, $newh ) ;
			$posx = $pdf->GetX() ;
//			$posy = $posy + 2*$zeilenhoehe ;
			$posy = $posy + $newh + $zeilenhoehe ;
			$pdf->setXY( $posx, $posy); 
			}
		}

//	$pdf->Ln( );

	$w=array( 60, 70 ) ;
	$tb = $textbreite - 60 ;
	if( $lage <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Lage:', 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $lage );
		}

	if( $zufahrtlage <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Zufahrt (PKW, Rad):', 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $zufahrtlage );
		}

	if( $anbindung <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Öffentliche Anbindung:'), 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $anbindung );
		}

	if( $zugang <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Zugang:', 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $zugang );
		}

	if( $lagegrundstk <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Lage am Grundstück:'), 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $lagegrundstk );
		}

	if( $nachbarbau <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Nachbarbebauung:', 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $nachbarbau );
		}

	if( $ausrichtung <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Ausrichtung:', 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $ausrichtung );
		}

	if( $oeffentlich <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Öffentliche Einrichtungen:'), 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $oeffentlich );
		}

	if( $gruenraum <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Grünraum / Nahversorgung:'), 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $gruenraum );
		}

	if( $sonstiges <> '' ) {
		$pdf->Cell( $w[0], $zeilenhoehe, 'Sonstiges:', 0, 0, 'L' ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ; ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( $tb, $zeilenhoehe, $sonstiges );
		}

	$abf1 = "SELECT * FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid ORDER BY Bezeichnung" ;
	$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		$gebbezeich			= $row->Bezeichnung ;
		$gebaeudeart		= $row->Gebaeudeart ;
		$baujahr				= $row->Baujahr ;
		$bauweise				= $row->Bauweise ;
		$anzgeschosse		= $row->AnzGeschosse ;
		$geschosshoehe	= $row->Geschosshoehe ;
		$aufzug					= $row->Aufzug ;
		$besichtigtam		= $row->BesichtigtAm ;
		$gebbeschreib		= $row->Beschreibung ;
		$zustand				= $row->Zustand ;

		$pos = $h3hoehe + $zeilenhoehe ;
		if( $gebbezeich != '' AND $gebbezeich != '-' ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $gebaeudeart != '' ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $baujahr != 0 ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $bauweise != '' ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $anzgeschosse > 0 ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $geschosshoehe > 0 ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $aufzug ) {
			$pos = $pos + $zeilenhoehe ;
			}
//		$besichtigtam
			$pos = $pos + $zeilenhoehe ;
		if( $gebbeschreib != '' ) {
			$pos = $pos + $zeilenhoehe ;
			}
		if( $zustand != '-' AND $zustand != '' ) {
			$pos = $pos + $zeilenhoehe ;
			}

		$gibtes = false ;
		$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Titelbild" ;
		$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $erg1 ) ) {
			$pfad = $row->DokumentenPfad . $row->Dokument ;
			if (file_exists( $pfad )) {
				$pixw = 174 ;
				list( $width, $height ) = getimagesize( $pfad ) ;
				if( $width > $pixw ) {
					$proz = $pixw * 100 / $width ;
					$neww = round( $width * $proz / 100, 2 );
					$newh = round( $height * $proz / 100, 2 );
					}
				$pos = $posy + $newh + 2 * $zeilenhoehe ;
				$gibtes = true ;
				}
			}

		$seitenpos = $pdf->GetY() ;
		if( $seitenhoehe - $seitenpos < $pos ) {
			$pdf->AddPage() ;
			}

		$umlaute = iconv('UTF-8', 'windows-1252', 'Gebäude- bzw. Objektbeschreibung' ) ;
		$pdf->Ln();
		$pdf->TOC_Entry( 'B.2.2  ' . $umlaute, 2 ) ;
		$pdf->SetFont( $schrift, 'B', $h3size ) ;
		$pdf->Cell( 0, 10, 'B.2.2  ' . $umlaute, 0, 1, 'L' ) ;

		if( $gibtes ) {
			$posx = $pdf->GetX() + 1 ;
			$posy = $pdf->GetY() + $zeilenhoehe ;
			$pdf->Image( $pfad, $posx, $posy, $neww, $newh ) ;
			$posx = $pdf->GetX() ;
			$posy = $posy + $newh + $zeilenhoehe ;
			$pdf->setXY( $posx, $posy); 
			}

		$w = array( 30, 140 ) ;

		if( $gebbezeich != '' AND $gebbezeich != '-' ) {
			$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
			$pdf->Cell( 150, $zeilenhoehe, $gebbezeich, '', 1, 'L' ) ;
			}
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = 80 ;
		if( $gebaeudeart != '' ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Objektart:', '', 0, 'L' ) ;
			$pdf->setX( $posx ); 
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $gebaeudeart, '', 1, 'L' ) ;
			}
		if( $baujahr != 0 ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Errichtung:', '', 0, 'L' ) ;
			$pdf->setX( $posx ); 
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $baujahr, '', 1, 'L' ) ;
			}
		if( $bauweise != '' ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Bauweise:', '', 0, 'L' ) ;
			$pdf->setX( $posx ); 
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $bauweise, '', 1, 'L' ) ;
			}
		if( $anzgeschosse > 0 ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Geschossanzahl:', '', 0, 'L' ) ;
			$pdf->setX( $posx ); 
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $anzgeschosse, '', 1, 'L' ) ;
			}
		if( $geschosshoehe > 0 ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Geschosshöhe:' ), '', 0, 'L' ) ;
			$pdf->setX( $posx ); 
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $geschosshoehe, '', 1, 'L' ) ;
			}

		$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Aufzug:', '', 0, 'L' ) ;
		$pdf->setX( $posx ); 
		if( $aufzug ) {
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, 'Ja', '', 1, 'L' ) ;
			}
		else {
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, 'Nein', '', 1, 'L' ) ;
			}

		if( $besichtigtam != '0000-00-00' ) {
			$stich	= date( "Y.m.d", strtotime( $besichtigtam ) ) ;
			}
		else {
			$stich	= '-' ;
			}
		$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Besichtigt am:', '', 0, 'L' ) ;
		$pdf->setX( $posx ); 
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, $stich, '', 1, 'L' ) ;

		if( $gebbeschreib != '' ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Sonstiges:', '', 0, 'L' ) ;
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $gebbeschreib, '', 1, 'L' ) ;
			}
		if( $zustand != '-' AND $zustand != '' ) {
			$pdf->Cell( $w[ 0 ], $zeilenhoehe, 'Zustand:', '', 0, 'L' ) ;
			$pdf->setX( $posx ); 
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $zustand, '', 1, 'L' ) ;
			}
		$pdf->Ln( );
		}

	$abf1 = "SELECT Ausstattung FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		$ausstattung = $row->Ausstattung ;
		}
	if( $ausstattung != '' ) {
		$pdf->TOC_Entry( 'B.2.3 Ausstattung', 2 ) ;
		$pdf->SetFont( $schrift, 'B', $h3size ) ;
		$pdf->Cell( 0, 10, 'B.2.3  Ausstattung', 0, 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;
		$posx = $pdf->GetX() ;
		$posy = $pdf->GetY() ;
		$pdf->setXY( $posx, $posy); 
	  $pdf->MultiCell( 0, $zeilenhoehe, $ausstattung );
		}
?>
<!-- EOF -->
