<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

// Include the main FPDF library
	require( '../fpdf_181/fpdf.php' ) ;
	require( 'Subroutines/rotation.php' ) ;
	include( 'Subroutines/PDFextendsFPDF.php' ) ;
	include( 'Subroutines/GetHeadFoot.php' ) ;

	$paragr6 = false ;
	$paragr9 = true ;
	$printnow = false ;
	$ready = false ;
	$inhalt =  $_REQUEST[ 'wie' ] ;

	if( $inhalt == 1 OR $inhalt == 3 ) {
		$vorabzug = true ;
		}
	else { 
		$vorabzug = false ;
		}

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abf = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	while( !$ready ) {
		$pdf=new PDF( 'P', 'mm', 'A4' );
		$pdf->startPageNums();
		$pdf->AliasNbPages();

// --------------- Deckblatt ---------------------------------------------------------
		include( 'Gutachten_Kapitel/FirstPage.php' ) ;

// --------------- Inhaltsverzeichnis ------------------------------------------------
		$pdf->AddPage() ;
		$pdf->SetFont( $schrift, 'B', $h1size ) ;
		$ueberschrift = 'Inhaltsverzeichnis' ;
		$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
		$pdf->SetFont( $schrift, '', $schriftsize ) ;

		if( $printnow ) {
			$toc = $_toc ;
			$i = 0 ;
			$anz = count( $toc ) ;
			while( $i < $anz ) {
				$was	= $toc [ $i ][ 't' ] ;
				$wo		= $toc [ $i ][ 'p' ] - 1 ;

				$lg		= strlen( $was ) ;
				$nix	= strpos( $was, ' ' ) ;
				$was1	= substr( $was, 0, $nix ) ;
				$was2	= substr( $was, $nix+1, $lg ) ;

				$lev = $toc [ $i ][ 'l' ] ;


				if( $lev == 0 ) {
					$left = 3;
					}
				else {
					if( $lev == 1 ) {
						$left = 8 ;
						$lev = 4 ;
						}
					else {
						$left = 10 ;
						$lev = 13 ;
						}
					$pdf->Cell( $lev ) ;
					}

	    //Filling dots
				$sizewas = $pdf->GetStringWidth( $was2 ) ;
				$sizewo = $pdf->GetStringWidth( $wo ) ;
				$dotslg = $textbreite - $sizewas - $lev - $sizewo - $left - 2 ;
				$dots = str_repeat( '.', $dotslg ) ;

				$pdf->Cell( $left, $zeilenhoehe, $was1, 0, 0, 'L' ) ;
				$pdf->Cell( $sizewas+2, $zeilenhoehe, $was2, 0, 0, 'L' ) ;
				$pdf->Cell( $dotslg, $zeilenhoehe, $dots, 0, 0, 'L' ) ;
				$pdf->Cell( $sizewo+1, $zeilenhoehe, $wo, 0, 1, 'R' ) ;
				$i++ ;
				}
			}

// --------------- Allgemeine Angaben ------------------------------------------------ 
		$pdf->AddPage() ;
		$pdf->SetFont( $schrift, 'B', $h1size ) ;
		$ueberschrift = 'A  Allgemeine Angaben' ;
		$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
		$pdf->TOC_Entry( $ueberschrift, 0 ) ;
		include( 'Gutachten_Kapitel/A1.php' ) ;
		include( 'Gutachten_Kapitel/A2.php' ) ;
		include( 'Gutachten_Kapitel/A3.php' ) ;
		include( 'Gutachten_Kapitel/A4.php' ) ;

// --------------- Befund ------------------------------------------------------------
		$pdf->AddPage() ;
		$pdf->SetFont( $schrift, 'B', $h1size ) ;
		$ueberschrift = 'B  Befund' ;
		$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
		$pdf->TOC_Entry( $ueberschrift, 0 ) ;
		$nixpage = false ;
		include( 'Gutachten_Kapitel/B1.php' ) ;
		include( 'Gutachten_Kapitel/B2.php' ) ;
		include( 'Gutachten_Kapitel/B3.php' ) ;
		include( 'Gutachten_Kapitel/B4.php' ) ;
		include( 'Gutachten_Kapitel/B5.php' ) ;
		include( 'Gutachten_Kapitel/B6.php' ) ;

// --------------- Gutachten ---------------------------------------------------------
		$pdf->SetFont( $schrift, 'B', $h1size ) ;
		$ueberschrift = 'C  Gutachten' ;
		$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
		$pdf->TOC_Entry( $ueberschrift, 0 ) ;
		include( 'Gutachten_Kapitel/C1.php' ) ;
		include( 'Gutachten_Kapitel/C2.php' ) ;
		include( 'Gutachten_Kapitel/C3.php' ) ;
		include( 'Gutachten_Kapitel/C4.php' ) ;
		include( 'Gutachten_Kapitel/C5.php' ) ;
		include( 'Gutachten_Kapitel/C6.php' ) ;
		include( 'Gutachten_Kapitel/C7.php' ) ;
		include( 'Gutachten_Kapitel/C8.php' ) ;
		include( 'Gutachten_Kapitel/C9.php' ) ;
		include( 'Gutachten_Kapitel/C10.php' ) ;
		include( 'Gutachten_Kapitel/C11.php' ) ;
		include( 'Gutachten_Kapitel/Legende.php' ) ;

// --------------- Beilagen ----------------------------------------------------------
		$pdf->AddPage() ;
		$pdf->SetFont( $schrift, 'B', $h1size ) ;
		$ueberschrift = 'D  Beilagen' ;
		$pdf->Cell( 0, $h1hoehe, $ueberschrift, 0, 1, 'L' ) ;
		$pdf->TOC_Entry( $ueberschrift, 0 ) ;
		include( 'Gutachten_Kapitel/D1.php' ) ;
		include( 'Gutachten_Kapitel/D2.php' ) ;
		include( 'Gutachten_Kapitel/D3.php' ) ;

// --------------- Rückseite ---------------------------------------------------------
		$pdf->stopPageNums();
		$pagenr	= $_SESSION[ 'nb' ] ;
		if( $pagenr % 2 != 0) {	
			$pdf->AddPage() ;
			}
		$nixhead = true ;
		$nixfoot = true ;
		$pdf->AddPage() ;

		$pdf->SetMargins( 0, $titlemargin_T );

		include( 'Gutachten_Kapitel/LastPage.php' ) ;

		if( $printnow ) {
			$ready = true ;
			}
		else {
			$printnow = true ;
			}
		}

// --------------- Save on Server und Ausgabe ----------------------------------------
	if( $vorabzug ) {
		$was = 'P9Va' ;
		}
	else {
		$was = 'P9' ;
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