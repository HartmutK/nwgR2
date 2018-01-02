<?php
	$kopf = true ;

	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Lageplan AND Drucken" ;
	$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		$pfad = $row->DokumentenPfad . $row->Dokument ;

		if (file_exists( $pfad )) {
			if ( $kopf ) {
				$pdf->AddPage() ;
				$pdf->SetFont( $schrift, 'B', $h2size ) ;
				$ueberschrift = 'D.2  Lageplan' ;
				$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, 'B', $h3size ) ;
				$pdf->TOC_Entry( $ueberschrift, 1 ) ;
				$kopf = false ;
				}
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
			$posy = $posy + $newh + $zeilenhoehe ;
			$pdf->setXY( $posx, $posy); 
			}
		}
?>
<!-- EOF -->
