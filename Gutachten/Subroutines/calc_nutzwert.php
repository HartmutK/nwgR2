<?php
function calc_nutzwert( $wert, $gesabschlag, $einh ) {

	switch( $einh ) {
		case '% vom NW/m2' :
			$calc = $wert * $gesabschlag ;
			break ;
		case 'NW/m2' :
			$calc = $wert * $gesabschlag ;
			break ;
		case 'je m2' :
			$calc = $wert ;
			break ;
		default:
			$calc = $wert * $gesabschlag / 100 ;
			break ;
		}
	$calc = number_format( round( $calc, 3 ), 3, ',', '.' ) ;

	return( $calc ) ;
	}
?>