<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	$inhalt = $_REQUEST[ 'wie' ] ;

	if( $inhalt = 1 OR $inhalt = 3 ) {
		$vorabzug = true ;
		}
	else { 
		$vorabzug = false ;
		if( $ausgestellt == 0 ) {
			$ausgestellt = date( 'd.m.Y' ) ;
			$abf0 = "UPDATE $db_gutachten SET Ausstellungsdatum = '$ausgestellt' WHERE GutachtenID = $gutachtenid" ;
			$erg0 = mysql_query( $abf0 )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			}
		}

	require( '../fpdf_181/fpdf.php' ) ;
	require( 'Subroutines/rotation.php' ) ;
	include( 'Subroutines/PDFextendsFPDF.php' ) ;
	include( 'Subroutines/GetHeadFoot.php' ) ;

// ------------------------------------------------------------------------
	$pdf=new PDF();

	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
	$pdf->AddPage() ;
	$pdf->startPageNums();
	$pdf->AliasNbPages();


	$pdf->SetFont( $schrift, 'B', $h1size ) ;
	$ueberschrift = 'C Gutachten' ;
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

// ------------------------------------------------------------
$pdf->stopPageNums();


// Generate TOC at End
//$anzpages = $_SESSION[ 'nb' ] ;
//if ( $anzpages & 1 ) {
//	$pdf->AddPage() ;
//	}
//$pdf->Cell( 0, $h1hoehe, 'Anz = ' . $anzpages, 0, 1, 'L' ) ;


// Ausgabe// Ausgabe
	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
?>
<!-- EOF -->