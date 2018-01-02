<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

// Include the main FPDF library (search for installation path).
	require( '../fpdf_181/fpdf.php' ) ;
	require( 'Subroutines/rotation.php' ) ;
	include( 'Subroutines/PDFextendsFPDF.php' ) ;
	include( 'Subroutines/GetHeadFoot.php' ) ;

	$nixhead = false ;
	$nixfoot = false ;
	$paragr6 = true ;
	$paragr9 = false ;

	$inhalt =  $_REQUEST[ 'wie' ] ;

	if( $inhalt == 1 OR $inhalt == 3 ) {
		$vorabzug = true ;
		}
	else { 
		$vorabzug = false ;
		}

	$anz_weobjekte = 0 ;
	$anz_sonstiges = 0 ;
	$anz_stelplatz = 0 ;
	$sonstiges = '' ;

	$widmungen = array() ;
	$abfrage = "SELECT Widmung, COUNT( `Widmung` ) as anz_Widmung FROM Wohnungen LEFT JOIN Gebaeude ON Wohnungen.InGebaeude = Gebaeude.GebaeudeID\n"
    . "	WHERE Gebaeude.InGutachtenID = $gutachtenid GROUP BY Widmung";
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$widmg = $row->Widmung ;
		$anz = $row->anz_Widmung ;
		$anz_weobjekte = $anz_weobjekte + $anz ;
		if( $widmg == 'Wohnung' ) {
			$anz_wohnung = $anz ;
			}
		elseif( strpos($widmg, 'stellplatz') !== false ) {
			$anz_stelplatz = $anz_stelplatz + $anz ;
			$widmungen [ ] = array( 'widmg'=>$widmg, 'anz'=>$anz ) ;
			}
		else {
			$anz_sonstiges = $anz_sonstiges + $anz ;
			if( $sonstiges != '' ) {
				$sonstiges = $sonstiges . ', ' ;
				}
			$sonstiges = $sonstiges . $anz . ' ' . $widmg ;
			}
		}

	$abfrage = "SELECT * FROM $db_txt WHERE TxtArt = 'P6-1'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	if( $row ) {
		$txt1 = $row->txt ;
		}
	else {
		$txt1 = '' ;
		}

	$abfrage = "SELECT * FROM $db_txt WHERE TxtArt = 'P6-2'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	if( $row ) {
		$txt2 = $row->txt ;
		}
	else {
		$txt2 = '' ;
		}

	$abfrage = "SELECT * FROM $db_txt WHERE TxtArt = 'P6-3'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	if( $row ) {
		$txt3 = $row->txt ;
		}
	else {
		$txt3 = '' ;
		}

	$abfrage = "SELECT Gemeinde FROM $db_kastral WHERE Nummer = '$grundbuch'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	if( $row ) {
		$gemde = $row->Gemeinde ;
		}
	else {
		$gemde = '' ;
		}

	$pdf=new PDF( 'P', 'mm', 'A4' );

// Deckblatt
	include( 'Gutachten_Kapitel/FirstPage.php' ) ;

// Seite 1
	$pdf->AddPage();
	$pdf->SetFont( $schrift, 'B', $verybigsize ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + 25 ;
	$pdf->setXY( $posx, $posy ); 
	$pdf->Cell( $textbreite, 20, iconv('UTF-8', 'windows-1252', 'Gutachten' ), '', 1, 'C' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $zeilenhoehe ;
	$pdf->setXY( $posx, $posy ); 
  $pdf->MultiCell( 0, $zeilenhoehe, $txt1, '', 'L' ) ;
	$pdf->Ln() ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy ); 

	$pdf->Cell( 72, $zeilenhoehe, 'EZ:', 0, 0, 'L' ) ;
	$pdf->Cell( 80, $zeilenhoehe, $ez, 0, 1, 'L' ) ;
	$pdf->Cell( 72, $zeilenhoehe, 'Grundbuch:', 0, 0, 'L' ) ;
	$pdf->Cell( 80, $zeilenhoehe, $grundbuch . ' ' . $gemde, 0, 1, 'L' ) ;
	$pdf->Cell( 72, $zeilenhoehe, 'Bezirksgericht:', 0, 0, 'L' ) ;
	$pdf->Cell( 80, $zeilenhoehe, $gericht, 0, 1, 'L' ) ;
	$pdf->Cell( 72, $zeilenhoehe, 'GST-NR:', 0, 0, 'L' ) ;
	$pdf->Cell( 80, $zeilenhoehe, $gst_nr, 0, 1, 'L' ) ;
	$pdf->Cell( 72, $zeilenhoehe, 'GST-Adresse:', 0, 0, 'L' ) ;
	$pdf->Cell( 80, $zeilenhoehe, $plz . ' ' . $ort. ', ' . $str , 0, 1, 'L' ) ;

	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $zeilenhoehe ;
	$pdf->setXY( $posx, $posy ); 
  $pdf->MultiCell( 0, $zeilenhoehe, $txt2, '', 'L' ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() + $zeilenhoehe ;
	$pdf->setXY( $posx, $posy ); 

	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( 72, $zeilenhoehe, 'Wohnungen:', 0, 0, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$pdf->Cell( 60, $zeilenhoehe, 'Gesamtzahl der Wohnungen: '. $anz_wohnung, 0, 1, 'L' ) ;

	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( 72, $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Sonstige selbstständige Räumlichkeiten:' ), 0, 0, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	if( $anz_sonstiges > 0 ) {
		$snst = $anz_sonstiges . ' => ' . $sonstiges ;
		}
	else {
		$snst ='Keine' ;
		}
	$pdf->Cell( 60, $zeilenhoehe, $snst, 0, 1, 'L' ) ;

	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( 72, $zeilenhoehe, iconv('UTF-8', 'windows-1252', 'Abstellplätze für Kraftfahrzeuge:' ), 0, 0, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	$anz1 = 0 ;
	$cnt = 0 ;
	$anz = count( $widmungen ) ;
	while( $cnt < $anz ) {
		$anz1 = $widmungen [ $cnt ][ 'anz' ] ;
		$cnt++ ;
		}

//	$pdf->Cell( 7, $zeilenhoehe, $anz1, 0, 0, 'L' ) ;
	$posx = $pdf->GetX() ;
	$posy = $pdf->GetY() ;
	$pdf->setXY( $posx, $posy );
	if( $anz1 == 0 ) {
		$txt3 = 'Keine' ; 
		}
	else {
		$txt3 = $anz1 . ' ' . $txt3 ; 
		}
  $pdf->MultiCell( 0, $zeilenhoehe, $txt3, '', 'L' ) ;

	$pdf->Ln() ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;
	$pdf->Cell( 72, $zeilenhoehe, 'insgesamt:', 0, 0, 'L' ) ;
	$pdf->SetFont( $schrift, 'B', $schriftsize ) ;
	$pdf->Cell( 60, $zeilenhoehe, $anz_weobjekte . ' wohnungseigentumstaugliche Objekte', 0, 1, 'L' ) ;
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

// Grundlagen/Unterlagen
	$pdf->AddPage();
	$paragr6 = true ;
	include( 'Gutachten_Kapitel/B5.php' ) ;

// Rückseite
	$pdf->AddPage();
	include( 'Gutachten_Kapitel/LastPage.php' ) ;

// --------------- Save on Server und Ausgabe -----------------------------------------
	if( $vorabzug ) {
		$was = 'P6Va' ;
		}
	else {
		$was = 'P6' ;
		}

	$weg = '../GutachtenPDF/' . $gutachtenid ;
	if( !file_exists( $weg ) ) {
		mkdir( $weg, 0777, true) ;
		}

	$d = date( 'Ymd' ) ;
	$t = date( 'His' ) ;
	$t = $d . '-' . $t ;
	$l = strlen( $fn ) - 4 ;
	$f = substr( $fn, 0, $l ) ;
	$fn = $f . '-' .  $was . '-' . $t . '.PDF' ;
	$dat = $weg . '/' . $fn ;

	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
	$pdf->Output( 'F', $dat ); 
	$frage	= "INSERT INTO Urkundenverzeichnis ( `ID`, `GutachtenID`, `Wer`, `Was`, `Pfad`, `Datei`, `Inhalt` ) 
						VALUES( NULL, '$gutachtenid', '$mitarbeiter', '$was', '$weg', '$fn', '' )" ;
//						VALUES( NULL, '$gutachtenid', '$mitarbeiter', '$was', '$fn', 'mysqli_real_escape_string (file_get_contents ( $weg )' )" ;
//	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

?>
<!-- EOF -->