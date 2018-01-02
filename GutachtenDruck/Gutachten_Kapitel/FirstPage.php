<?php
unset( $_SESSION[ 'nixfoot' ] ) ;
$_SESSION[ 'nixfoot' ] = true ;

$nixhead = true ;
$nixfoot = true ;

$pdf->SetAutoPageBreak( false, 1 ) ;
$pdf->AddPage( 'P', 'A4' ) ;

$pdf->SetMargins( $titlemargin_L, $titlemargin_T, $titlemargin_R, $titlemargin_B ) ;


$pdf->SetTextColor( 255, 255, 255 ) ;

$pic_h = $page_H - $titlemargin_T - $titlemargin_B ;
$pic_r = $page_W - $titlemargin_R - $titlpicright_W ;
$pdf->Image( '../Bilder/Baukult_Gutachten_links.png', $titlemargin_L, $titlemargin_T, $titlpicleft_W, $pic_h, 'png' ) ;
$pdf->Image( '../Bilder/Baukult_Gutachten_rechts.png', $pic_r, $titlemargin_T, $titlpicright_W, $pic_h, 'png' ) ;
$pdf->SetFont( $schrift, '', $h2size ) ;

// hier kommt das Titelbild
$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
$abf1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Seite1Foto" ;
$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
$row = mysql_fetch_object( $erg1 ) ;
if( $row ) {
	$pfad = $row->DokumentenPfad . $row->Dokument ;
	}
else {
	$pfad = '' ;
	}

$posy = $pdf->GetY() ;

if( $pfad != '' AND file_exists( $pfad ) ) {
	$posx = $titlemargin_L ;
	$posy = 87.5 - $titlemargin_T ;
	$pdf->Image( $pfad, $posx, $posy, 105, 105 ) ;
	}
else {
	$posy = $posy + 72 ;
	}

if( $paragr6 ) { 
	$ueberschrift = iconv('UTF-8', 'windows-1252', 'gem. §6 WEG idgF.' ) ;
	}
else {
	$ueberschrift = iconv('UTF-8', 'windows-1252', 'gem. §9 WEG idgF.' ) ;
	}

$posx = $titlemargin_L + 21 ;


$posy = $posp ;
$pdf->setXY( $posx, $posy ); 

$pdf->Cell( 80, $zeilenhoehe, $ueberschrift, '', 1, 'L' ) ;

if( $plz == 0 OR $ort == '' ) {
	$adresse = iconv('UTF-8', 'windows-1252', 'Adresse unvollständig' ) ;
	}
else {
	$adresse = $plz . ' ' . $ort ;
	}
$posy = $posy + $zeilenhoehe + 2 ;
$pdf->setXY( $posx, $posy ); 
$pdf->Cell( 0, $zeilenhoehe, $str, '', 1, 'L' ) ;

$posy = $posy + $zeilenhoehe ;
$pdf->setXY( $posx, $posy); 
$pdf->Cell( 0, $zeilenhoehe, $adresse, '', 1, 'L' ) ;

if( $vorabzug ) {
	$posy = $posy + $zeilenhoehe ;
	$pdf->setXY( $posx, $posy ); 
	$pdf->Cell( 0, $zeilenhoehe, 'Vorabzug', '', 1, 'L' ) ;
	$posy = $posy + 5 * $zeilenhoehe ;
	$titeldat	= date( 'd.m.Y', strtotime( $stichtag ) ) ;
	}
else {
	$posy = $posy + 6 * $zeilenhoehe ;
	$titeldat	= date( 'd.m.Y', strtotime( $ausgestellt ) ) ;
	}

$posy = $posgz ;

$pdf->setXY( $posx, $posy); 
$pdf->Cell( 75, $zeilenhoehe, 'GZ: ' . $gz . '-' . $index, '', 1, 'R' ) ;

$posy = $posy + $zeilenhoehe ;
$pdf->setXY( $posx, $posy ); 
$pdf->Cell( 75, $zeilenhoehe, 'Wien, am ' . $titeldat, '', 1, 'R' ) ;

$pdf->SetTextColor( 51, 51, 51 ) ;
$pdf->SetAutoPageBreak( true, $pagemargin_B ) ;

$pdf->SetMargins( $pagemargin_L, $pagemargin_T ) ;

$nixhead = false ;
$nixfoot = false ;
?>
<!-- EOF -->
