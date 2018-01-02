<?php
	session_start( ) ;

	include( 'php/DBselect.php' ) ;
	include( 'php/set_angemeldet.php' ) ;
	include( 'php/clear_sessions.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'index' ;

	if( isset( $_SESSION[ 'anmelden' ] ) ) {
		$anmelden = $_SESSION[ 'anmelden' ] ;
		}
	else {
		$_SESSION[ 'anmelden' ] = array() ;
		$anmelden = array() ;
		$anmelden = array( 'id'=>0, 'angemeldet'=>false, 'mail'=>'', 'adm'=>'0' ) ;
		$_SESSION[ 'anmelden' ] = $anmelden ;
		}

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	require( 'fpdf_181/fpdf.php' ) ;
	require( 'GutachtenDruck/Subroutines/rotation.php' ) ;
	include( 'GutachtenDruck/Subroutines/PDFextendsFPDF.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'index' ;

	$nixhead = true ;
	$nixfoot = true ;

//--------------------------------------------------------------------------------------
	$head=array( 'ID', 'Revit', iconv('UTF-8', 'windows-1252', 'Gebäude' ), 'Top', 'Was', 'Lage', 'Belegung', iconv('UTF-8', 'windows-1252', 'Fläche' ) );
	$w=array( 15, 15, 20, 31, 20, 25, 35, 15 ) ;
	$fn = 'imported.pdf' ;
	
	if( isset( $_POST[ 'okay' ] ) ) {
		$pdf=new PDF( );
		$pdf->SetFont( 'Arial', '', 10 ) ;
		$pdf->SetMargins( 20, 10, 10, 10 ) ;
		$pdf->AddPage( ) ;

		$pdf->Cell( $w[ 0 ], $zeilenhoehe, $head[ 0 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 1 ], $zeilenhoehe, $head[ 1 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 2 ], $zeilenhoehe, $head[ 2 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 3 ], $zeilenhoehe, $head[ 3 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 4 ], $zeilenhoehe, $head[ 4 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 5 ], $zeilenhoehe, $head[ 5 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 6 ], $zeilenhoehe, $head[ 6 ], 0, 0, 'L' ) ;
		$pdf->Cell( $w[ 7 ], $zeilenhoehe, $head[ 7 ], 0, 1, 'R' ) ;

		$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;

		$abf1 = "SELECT * FROM $db_importbim ORDER BY TOP, Ebene, Belegung" ;
		$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$f0	= $rw1->ID ;
			$f1	= $rw1->RevitID ;
			$f2	= $rw1->Zuordnung ;
			$f3	= $rw1->TOP ;
			$f4	= $rw1->Name ;
			$f5	= $rw1->Ebene ;
			$f6	= $rw1->Belegung ;
			$f7	= $rw1->Flche ;
			$f7	= round( $f7, 2 ) ;

			$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'R' ) ;
			}

	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
	}
//--------------------------------------------------------------------------------------
		include( "php/head.php" ) ;
		?>
		<div id="mainstammd">
			<form method="post" name='input_fields' class="login" enctype="multipart/form-data" >
				<button type="submit" Name="okay" class="okay_buttn"></button> 
				</form>
				</div>  <!-- main -->
			</div>  <!-- container -->
		</body>
	</html>
<!-- EOF -->