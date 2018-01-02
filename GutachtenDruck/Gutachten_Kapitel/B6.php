<?php
	$oldB1 = false ;
	
	$ueberschrift =  'B.6  Plangrundlagen' ;

	$pdf->AddPage() ;
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$abf1 = "SELECT COUNT( Grundriss ) AS Maxi FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundriss AND Drucken" ;
	$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $erg1 ) ;
	$maxi	= $row->Maxi ;


	$i = 1 ;
	$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundriss AND Drucken ORDER BY Reihenfolge DESC" ;
	$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		$bezch	= $row->Bezeichnung ;
		$beschr = $row->Beschreibung ;
		$pfad = $row->DokumentenPfad . $row->Dokument ;

		if( $bezch != '' ) {
			$pdf->SetFont( $schrift, 'B', $h3size ) ;
			$pdf->Cell( 0, $h3hoehe, $bezch, 0, 1, 'L' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			}

		if( $beschr != '' ) {
			$posx = $pdf->GetX() ;
			$posy = $pdf->GetY() ;
			$pdf->setXY( $posx, $posy); 
		  $pdf->MultiCell( $textbreite, $zeilenhoehe, $beschr );
			$pdf->Ln( ) ;
			}

		if( file_exists( $pfad ) ) {
			$pixw = 170 ;
			list( $width, $height ) = getimagesize( $pfad ) ;
			if( $width > $pixw ) {
				$proz = $pixw * 95 / $width ;
				$neww = round( $width * $proz / 100, 2 );
				$newh = round( $height * $proz / 100, 2 );
				}

			$posx = $pdf->GetX() - 1 ;
			$posy = $pdf->GetY() ;

			if( $oldB1 ) {
				$posy = $posy + $zeilenhoehe / 2 ;
				}
			else {
				$posy = $posy + $zeilenhoehe / 4 ;
				}

			$oldB1 = true ;

			if( $printnow ) $pdf->Image( $pfad, $posx, $posy, $neww, $newh ) ;

			if( $i < $maxi OR $paragr9 ) {
				$pdf->AddPage() ;
				}
			$i++ ;
			}
		}
?>
<!-- EOF -->
