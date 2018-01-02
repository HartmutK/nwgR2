<?php
function make_formel( $a, $b, $zeh, $t, $rechne, $addieren ) {

	$leng = strlen( $rechne ) ;

	$calc = `` ;
	$j = 0 ;
	while( $j < $leng ) {
		$c = substr( $rechne, $j, 1 ) ;
		switch( $c ) {
			case 'a' :
				$calc = $calc . $a ;
				break ;
			case 'b' :
				$calc = $calc . $b ;
				break ;
			case 'c' :
				$calc = $calc . $zeh ;
				break ;
			case 't' :
				$calc = $calc . $t ;
				break ;
			case '(' :
				$calc = $calc . '(' ;
				break ;
			case ')' :
				$calc = $calc . ')' ;
				break ;
			case '+' :
				$calc = $calc . '+' ;
				break ;
			case '-' :
				$calc = $calc . '-' ;
				break ;
			case '*' :
				$calc = $calc . '*' ;
				break ;
			case '/' :
				$calc = $calc . '/' ;
				break ;
			case '0' :
				$calc = $calc . '0' ;
				break ;
			case '1' :
				$calc = $calc . '1' ;
				break ;
			case '2' :
				$calc = $calc . '2' ;
				break ;
			case '3' :
				$calc = $calc . '3' ;
				break ;
			case '4' :
				$calc = $calc . '4' ;
				break ;
			case '5' :
				$calc = $calc . '5' ;
				break ;
			case '6' :
				$calc = $calc . '6' ;
				break ;
			case '7' :
				$calc = $calc . '7' ;
				break ;
			case '8' :
				$calc = $calc . '8' ;
				break ;
			case '9' :
				$calc = $calc . '9' ;
				break ;
			case 'W' :
				$calc = $calc . 'sqrt' ;
				$j = $j + 5 ;
				break ;
			case 'P' :
				$calc = $calc . '3.1416*' ;
				$j = $j + 2 ;
				break ;
			default:
				break ;
			}
		$j++ ;
		}

	if( $leng > 0 AND !$addieren ) { 
		$calc = '-(' .  $calc . ')' ;
		} 

	return( $calc ) ;
	}
?>