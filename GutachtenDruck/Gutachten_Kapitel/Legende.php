<?php
$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

$ab1 = "SELECT COUNT( ID ) AS Anz FROM $db_abkuerz WHERE Aktiv AND ( InGutachtenID = 0 OR InGutachtenID = $gutachtenid )" ;
$er1 = mysql_query( $ab1 ) OR die( "Error: $ab1 <br>". mysql_error() ) ;
$rw1 = mysql_fetch_object( $er1 ) ;
$zahl = $rw1->Anz ;

$ab1 = "SELECT COUNT( Distinct Lage ) AS Anz FROM $db_wohnung WHERE InGutachten = $gutachtenid" ;
$er1 = mysql_query( $ab1 ) OR die( "Error: $ab1 <br>". mysql_error() ) ;
$rw1 = mysql_fetch_object( $er1 ) ;
$zahl = $zahl + $rw1->Anz ;

$pos = $h2hoehe + $zahl * $zeilenhoehe ;
$seitenpos = $pdf->GetY() ;

if( $seitenhoehe - $seitenpos < $pos ) {
	$pdf->AddPage() ;
	}

$w=array( 0, 20, 70 ) ;

$ueberschrift = 'C.12  Legende' ;
$pdf->SetFont( $schrift, 'B', $h2size ) ;
$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
$pdf->TOC_Entry( $ueberschrift, 1 ) ;

$ueberschrift = iconv('UTF-8', 'windows-1252', 'Abkürzungen' ) ;
$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
$pdf->SetFont( $schrift, '', $schriftsize ) ;

$ab1 = "SELECT * FROM $db_abkuerz WHERE Aktiv AND ( InGutachtenID = 0 OR InGutachtenID = $gutachtenid ) ORDER BY Kuerzel" ;
$er1 = mysql_query( $ab1 ) OR die( "Error: $ab1 <br>". mysql_error() ) ;
while( $rw1 = mysql_fetch_object( $er1 ) ) {
	$kurz = $rw1->Kuerzel ;
	$lang = $rw1->Langtext ;
	$pdf->Cell( $w[1], $zeilenhoehe, $kurz, '', 0, 'L' ) ;
	$pdf->Cell( $w[2], $zeilenhoehe, $lang, '', 1, 'L' ) ;
	} //while - array füllen

$ueberschrift = 'Geschossbezeichnungen' ;
$pdf->SetFont( $schrift, 'B', $h2size ) ;
$pdf->Cell( 0, $h2hoehe, $ueberschrift, 0, 1, 'L' ) ;
$pdf->SetFont( $schrift, '', $schriftsize ) ;

$ab0 = "SELECT Distinct Lage FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
$er0 = mysql_query( $ab0 )  OR die( "Error: $ab0 <br>". mysql_error() ) ;
while( $rw0 = mysql_fetch_object( $er0 ) ) {
	$was = $rw0->Lage ;
	$ab1 = "SELECT Lage FROM $db_lagen WHERE Kuerzel = '$was'" ;
	$er1 = mysql_query( $ab1 ) OR die( "Error: $ab1 <br>". mysql_error() ) ;
	$rw1 = mysql_fetch_object( $er1 ) ;
	if( $rw1 ) {
		$lage = $rw1->Lage ;
		}
	else {
		$lage = '' ;
		}
	$pdf->Cell( $w[1], $zeilenhoehe, $was, '', 0, 'L' ) ;
	$pdf->Cell( $w[2], $zeilenhoehe, $lage, '', 1, 'L' ) ;
	}
?>
<!-- EOF -->
