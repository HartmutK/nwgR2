<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/set_angemeldet.php' ) ;
	include( '../php/clear_sessions.php' ) ;

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

	require( '../fpdf_181/fpdf.php' ) ;
	require( '../GutachtenDruck/Subroutines/rotation.php' ) ;
	include( '../GutachtenDruck/Subroutines/PDFextendsFPDF.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'index' ;

	$head1		= 'Gutachten' ;
	$head1txt	= '' ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	$nixhead = true ;
	$nixfoot = true ;
	$nixnb	 = true ;

//--------------------------------------------------------------------------------------
	$head=array( 'ID', 'OZ', 'Top', 'Belegung', 'Ebene', 'Raumbezeichnung', iconv('UTF-8', 'windows-1252', 'Fläche' ), 'Widmung' );
	$w=array( 15, 15, 20, 25, 25, 25, 30, 15, 15 ) ;
	
//	if( isset( $_POST[ 'okay' ] ) ) {
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
		$pdf->Cell( $w[ 6 ], $zeilenhoehe, $head[ 6 ], 0, 0, 'R' ) ;
		$pdf->Cell( $w[ 7 ], $zeilenhoehe, $head[ 7 ], 0, 1, 'L' ) ;

		$pdf->Cell( $textbreite, 1, '', 'T', 1, 'L' ) ;

		$abf = "SELECT COUNT( ID ) AS mitnix FROM $db_importbim" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw	 = mysql_fetch_object( $erg ) ;
		$mitnix = $rw->mitnix ;

		$abf = "SELECT COUNT( ID ) AS wieviel FROM $db_importbim WHERE RevitID = ''" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw	 = mysql_fetch_object( $erg ) ;
		$wieviel = $rw->wieviel ;
		if( $mitnix == $wieviel ) {
			$pdf->Ln( ) ;
			$pdf->SetFont( $schrift, 'B', $h2size ) ;
			$pdf->Cell( 0, $h2hoehe, iconv('UTF-8', 'windows-1252', 'Die Spalte ID ist nicht ausgefüllt.' ), 0, 1, 'L' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			}
		else {
			$neu = true ;
			$abf = "SELECT RevitID, count( * ) FROM $db_importbim GROUP BY RevitID HAVING count(*) > 1" ;
			$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			while( $rw1 = mysql_fetch_object( $erg ) ) {
				if( $neu ) {
					$pdf->Ln( ) ;
					$pdf->SetFont( $schrift, 'B', $h2size ) ;
					$pdf->Cell( 0, $h2hoehe, 'Die ID ist mehrfach vergeben.', 0, 1, 'L' ) ;
					$pdf->SetFont( $schrift, '', $schriftsize ) ;
					$neu = false ;
					}

				$f0	= $rw1->RevitID ;
				$abf1 = "SELECT * FROM $db_importbim WHERE RevitID = '$f0'" ;
				$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
				while( $rw1 = mysql_fetch_object( $erg1 ) ) {
					$f1	= $rw1->Zuordnung ;
					$f2	= $rw1->TOP ;
					$f3	= $rw1->Name ;
					$f4	= $rw1->Ebene ;
					$f5	= $rw1->Belegung ;
					$f6	= round( $rw1->Flche, 2 ) ;
					$f6 = number_format( $f6, 2, ',', '.' ) ;
					$f7	= $rw1->SSR ;

					$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
					$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
					$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
					$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
					$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
					$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
					$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
					$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
					}
				}
			}
//--------------------------------------------------------------
		$neu = true ;
		$abf = "SELECT * FROM $db_importbim WHERE Name != 'Allg.' AND Name != 'NF' AND Name != 'Z' AND Name != 'Zus' ORDER BY RevitID" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			if( $neu ) {
				$pdf->Ln( ) ;
				$pdf->SetFont( $schrift, 'B', $h2size ) ;
				$pdf->Cell( 0, $h2hoehe, iconv('UTF-8', 'windows-1252', 'Die Belegung ist ungültig.' ), 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$neu = false ;
				}

			$f0	= $rw->RevitID ;
			$f1	= $rw->Zuordnung ;
			$f2	= $rw->TOP ;
			$f3	= $rw->Name ;
			$f4	= $rw->Ebene ;
			$f5	= $rw->Belegung ;
			$f6	= round( $rw->Flche, 2 ) ;
			$f6 = number_format( $f6, 2, ',', '.' ) ;
			$f7	= $rw->SSR ;

			$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
			$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
			}
//--------------------------------------------------------------
		$neu = true ;
		$abf = "SELECT * FROM $db_importbim WHERE Flche = 0 ORDER BY RevitID" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			if( $neu ) {
				$pdf->Ln( ) ;
				$pdf->SetFont( $schrift, 'B', $h2size ) ;
				$pdf->Cell( 0, $h2hoehe, iconv('UTF-8', 'windows-1252', 'Flächenangaben kontrollieren.' ), 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$neu = false ;
				}

			$f0	= $rw->RevitID ;
			$f1	= $rw->Zuordnung ;
			$f2	= $rw->TOP ;
			$f3	= $rw->Name ;
			$f4	= $rw->Ebene ;
			$f5	= $rw->Belegung ;
			$f6	= round( $rw->Flche, 2 ) ;
			$f6 = number_format( $f6, 2, ',', '.' ) ;
			$f7	= $rw->SSR ;

			$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
			$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
			}
//--------------------------------------------------------------
		$neu = true ;
		$abf = "SELECT * FROM $db_importbim WHERE Ebene = '' OR Belegung = '' OR SSR = '' ORDER BY RevitID" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			if( $neu ) {
				$pdf->Ln( ) ;
				$pdf->SetFont( $schrift, 'B', $h2size ) ;
				$pdf->Cell( 0, $h2hoehe, 'Ebene, Raumbezeichnung oder Widmung fehlen.', 0, 1, 'L' ) ;
				$pdf->SetFont( $schrift, '', $schriftsize ) ;
				$neu = false ;
				}

			$f0	= $rw->RevitID ;
			$f1	= $rw->Zuordnung ;
			$f2	= $rw->TOP ;
			$f3	= $rw->Name ;
			$f4	= $rw->Ebene ;
			$f5	= $rw->Belegung ;
			$f6	= round( $rw->Flche, 2 ) ;
			$f6 = number_format( $f6, 2, ',', '.' ) ;
			$f7	= $rw->SSR ;

			$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
			$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
			}
//--------------------------------------------------------------
		$neu = true ;
		$abf = "SELECT * FROM $db_importbim ORDER BY RevitID" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$f5	= $rw->Belegung ;
			$f7	= $rw->SSR ;
			if( ( $f5 == iconv('UTF-8', 'windows-1252', 'Büro' ) AND $f7 != $f5 ) OR 
					( $f5 == 'Atelier' AND $f7 != $f5 ) OR 
					( $f5 == iconv('UTF-8', 'windows-1252', 'Geschäftslokal' ) AND $f7 != $f5 ) OR 
					( $f5 == 'Lokal' AND $f7 != $f5 ) OR 
					( $f5 == 'Kindergarten' AND $f7 != $f5 ) OR 
					( $f5 == 'Ordination' AND $f7 != $f5 ) ) {
				if( $neu ) {
					$pdf->Ln( ) ;
					$pdf->SetFont( $schrift, 'B', $h2size ) ;
					$pdf->Cell( 0, $h2hoehe, iconv('UTF-8', 'windows-1252', 'Raumbezeichnung und Widmung prüfen.' ), 0, 1, 'L' ) ;
					$pdf->SetFont( $schrift, '', $schriftsize ) ;
					$neu = false ;
					}

				$f0	= $rw->RevitID ;
				$f1	= $rw->Zuordnung ;
				$f2	= $rw->TOP ;
				$f3	= $rw->Name ;
				$f4	= $rw->Ebene ;
				$f5	= $rw->Belegung ;
				$f6	= round( $rw->Flche, 2 ) ;
				$f6 = number_format( $f6, 2, ',', '.' ) ;
				$f7	= $rw->SSR ;

				$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
				$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
				$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
				$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
				$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
				$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
				$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
				$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
				}
			}
//--------------------------------------------------------------
		$liste = array ( ) ;
		$abf = "SELECT * FROM $db_importbim ORDER BY RevitID" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$id	= $rw->ID ;
			$f0	= $rw->RevitID ;
			$f1	= $rw->Zuordnung ;
			$f2	= $rw->TOP ;
			$f3	= $rw->Name ;
			$f4	= $rw->Ebene ;
			$f5	= $rw->Belegung ;
			$f6	= round( $rw->Flche, 2 ) ;
			$f7	= $rw->SSR ;
			$abf1 = "SELECT RevitID FROM $db_importbim 
								WHERE ID != '$id' AND Zuordnung = '$f1' AND TOP = '$f2' AND Name = '$f3'AND TOP = '$f2' AND Ebene = '$f4' AND Belegung = '$f5' AND Flche = '$f6' AND SSR = '$f7' 
								ORDER BY RevitID" ;
			$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
			while( $rw1 = mysql_fetch_object( $erg1 ) ) {
				$rev = $rw1->RevitID ;
				$liste [ ] = $f0 ;
				$liste [ ] = $rev ;
				}
			}

		$anz = count( $liste ) ;
		if( $anz > 0 ) {
			$pdf->Ln( ) ;
			$pdf->SetFont( $schrift, 'B', $h2size ) ;
			$pdf->Cell( 0, $h2hoehe, iconv('UTF-8', 'windows-1252', 'Die Räume sind gleich.' ), 0, 1, 'L' ) ;
			$pdf->SetFont( $schrift, '', $schriftsize ) ;
			}

		$arr = array_unique( $liste ) ;
		foreach ( $arr as $revt ) {
			$abf = "SELECT * FROM $db_importbim WHERE RevitID = $revt" ;
			$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			$rw = mysql_fetch_object( $erg ) ;
			$f0	= $rw->RevitID ;
			$f1	= $rw->Zuordnung ;
			$f2	= $rw->TOP ;
			$f3	= $rw->Name ;
			$f4	= $rw->Ebene ;
			$f5	= $rw->Belegung ;
			$f6	= round( $rw->Flche, 2 ) ;
			$f6 = number_format( $f6, 2, ',', '.' ) ;
			$f7	= $rw->SSR ;

			$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
			$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
			$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
			$i++ ;
			}

//$pdf->Cell( 0, $zeilenhoehe, $abf1, 0, 1, 'L' ) ;
//$pdf->Cell( 0, $zeilenhoehe, 'found = ' . $id, 0, 1, 'L' ) ;

//--------------------------------------------------------------------------------------

	$fn = 'Checkin.pdf' ;

	ob_end_clean() ;
	$pdf->Output( 'I', $fn ); 
//	}
	?>
<!-- EOF -->