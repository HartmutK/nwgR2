<?php
	$kopf = true ;

	$abfrag1 = "SELECT COUNT( Grundbuch ) AS DS FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundbuch" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebn1 ) ;
	$anzds = $row->DS ;
	$i = 0 ;

	$abfrag1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundbuch ORDER BY Reihenfolge ASC" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebn1 ) ) {
		if( $kopf ) {
			$kopf = false ;
			$pdf->SetFont( $schrift, 'B', $h1size ) ;
			$ueberschrift = 'D.1  Grundbuchsauszug' ;
			$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
			$pdf->TOC_Entry( $ueberschrift, 1 ) ;
			}

		$i++ ;
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
			if( $i < $anzds) $pdf->AddPage() ;
			}
		}
?>
<!-- EOF -->
