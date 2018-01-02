<?php
	$oldB1 = false ;
	
	$ueberschrift =  'B.1  Grundbuchstand' ;

	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundbuch AND Reihenfolge = 1" ;
	$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		$dcpc = $row->Dokument ;
		$pfad = $row->DokumentenPfad . $dcpc ;

		if( file_exists( $pfad ) ) {
			$pixw = 165 ;
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
			}

		}
	if( !$nixpage ) {
		$pdf->AddPage() ;
		}
?>
<!-- EOF -->
