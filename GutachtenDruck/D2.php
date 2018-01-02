<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	$inhalt =  $_REQUEST[ 'wie' ] ;

	if( $inhalt = 1 OR $inhalt = 3 ) {
		$vorabzug = true ;
		}
	else { 
		$vorabzug = false ;
		}

	require( '../fpdf_181/fpdf.php' ) ;
	require( 'Subroutines/rotation.php' ) ;
	include( 'Subroutines/PDFextendsFPDF.php' ) ;
	include( 'Subroutines/GetHeadFoot.php' ) ;

// --------------------------------------------------------------------
// ------------- D Lageplan/Fotos -------------------------------------
//	Allg. Info
	$pdf=new PDF();

//	$pdf->startPageNums();
	$pdf->SetMargins( $pagemargin_L, $pagemargin_T );
	$pdf->AliasNbPages();
	$pdf->SetFont( $schrift, '', $schriftsize ) ;

	include( 'Gutachten_Kapitel/D2.php' ) ;

// -------------------------------------------------------------------

	ob_end_clean() ;
	$pdf->Output() ;
?>
<!-- EOF -->