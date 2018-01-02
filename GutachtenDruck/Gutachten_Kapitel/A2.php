<?php
	$pdf->SetFont( $schrift, 'B', $h2size ) ;
	$ueberschrift = 'A.2  AuftragnehmerInnen' ;
	$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
	$pdf->TOC_Entry( $ueberschrift, 1 ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$abfrage = "SELECT * FROM $db_user WHERE master" ;
	$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_user.php' ) ;
		$ma = $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
		}
	$pdf->Cell( $textbreite, $zeilenhoehe, $fabez, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $ma, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $faplz . ' ' . $faort . ' . ' . $fastr, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $firmennr . ' . ' . $fatel, '', 1, 'L' ) ;
	$pdf->Cell( $textbreite, $zeilenhoehe, $fawww . ' . ' . $userwww, '', 1, 'L' ) ;
?>
<!-- EOF -->
