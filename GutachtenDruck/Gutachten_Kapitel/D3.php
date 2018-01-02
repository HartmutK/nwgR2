<?php
	$kopf = true ;
	$links = true ;

	$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Bild AND Drucken ORDER BY Reihenfolge DESC" ;
	$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg1 ) ) {
		if( $kopf ) {
			$pdf->AddPage() ;
			$kopf = false ;
			$pdf->AddPage() ;
			$pdf->SetFont( $schrift, 'B', $h1size ) ;
			$ueberschrift = 'D.3  Fotos' ;
			$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
			$pdf->TOC_Entry( $ueberschrift, 1 ) ;
			}
		include( '../Subroutines/print_pic.php' ) ;
		}
?>
<!-- EOF -->
