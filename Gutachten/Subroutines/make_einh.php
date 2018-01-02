<?php
function make_einh( $wert, $einh ) {

	switch( $einh ) {
		case '% vom NW/m2' :
			$einh = iconv('UTF-8', 'windows-1252', '% vom NW/m²' ) ;
			break ;
		case 'NW/m2' :
			$einh = iconv('UTF-8', 'windows-1252', ' vom NW/m²' ) ;
			break ;
		case 'je m2' :
			$einh = iconv('UTF-8', 'windows-1252', ' je m²' ) ;
			break ;
		default:
			$einh = ' ' . $einh ;
			break ;
		}

	$einh = $wert . $einh ;

	return( $einh ) ;
	}
?>