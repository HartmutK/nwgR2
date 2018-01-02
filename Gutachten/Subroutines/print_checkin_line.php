<?php
	$f0	= $rw->RevitID ;
	$f1	= $rw->Zuordnung ;
	$f2	= $rw->TOP ;
	$f3	= $rw->Name ;
	$f4	= $rw->Ebene ;
	$f5	= $rw->Belegung ;
	$f6	= round( $rw->Flche, 2 ) ;
	$f7	= $rw->SSR ;

	$pdf->Cell( $w[ 0 ], $zeilenhoehe, $f0, 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 1 ], $zeilenhoehe, $f1, 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 2 ], $zeilenhoehe, $f2, 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 3 ], $zeilenhoehe, $f3, 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 4 ], $zeilenhoehe, $f4, 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 5 ], $zeilenhoehe, $f5, 0, 0, 'L' ) ;
	$pdf->Cell( $w[ 6 ], $zeilenhoehe, $f6, 0, 0, 'R' ) ;
	$pdf->Cell( $w[ 7 ], $zeilenhoehe, $f7, 0, 1, 'L' ) ;
	?>
<!-- EOF -->