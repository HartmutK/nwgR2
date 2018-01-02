<?php
		$pfad = $row->DokumentenPfad . $row->Dokument ;
		$bez	= $row->Bezeichnung ;
		$bild	= $row->Bild ;

		if( file_exists( $pfad ) ) {
			if( $bild AND $cntpic > 6 ) {
				$pdf->AddPage() ;
				$cntpic = 1 ;
				}
			$pixw = 160 ;
			list( $width, $height ) = getimagesize( $pfad ) ;
			if( $width > $pixw ) {
				$proz = $pixw * 50 / $width ;
				$neww = round( $width * $proz / 100, 2 );
				$newh = round( $height * $proz / 100, 2 );
				}

			if( $links ) {
				$links = false ;
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( 0, $zeilenhoehe, $bez, 0, 0, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$posx = $pagemargin_L ;
				$posy = $pdf->GetY() + $zeilenhoehe + 2 ;
				$pdf->setXY( $posx, $posy); 
				$pdf->Image( $pfad, $posx, $posy, $neww, $newh ) ;
				}
			else {
				$links = true ;
				$posx = $posx + 95 ;
				$posy = $posy - $zeilenhoehe - 2 ;
				$pdf->setXY( $posx, $posy); 
				$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
				$pdf->Cell( 0, $zeilenhoehe, $bez, 0, 0, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;

				$posx = $pagemargin_L + 95 ;
				$posy = $pdf->GetY() + $zeilenhoehe + 2 ;
				$pdf->setXY( $posx, $posy); 
				$pdf->Image( $pfad, $posx, $posy, $neww, $newh ) ;

				$posx = $pagemargin_L ;
				$posy = $posy + $newh + 2*$zeilenhoehe ;
				$pdf->setXY( $posx, $posy); 
				}

			$cntpic++ ;
			}
?>
<!-- EOF -->
