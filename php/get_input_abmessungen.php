<?php
$vonraumid	= $_SESSION[ 'raumid' ] ;
$raum_a			= $_POST[ 'room_a' ] ;
$raum_b			= $_POST[ 'room_b' ] ;
$raum_c			= $_POST[ 'room_c' ] ;
$raum_t			= $_POST[ 'room_t' ] ;
$form				= $_POST[ 'frm' ] ;

if( isset( $_POST[ 'add' ] ) ) { $addieren = true ; } else { $addieren = false ; }

$frg = "SELECT zuRechnen FROM $db_form WHERE Form = '$form'" ;
$erg = mysql_query( $frg ) OR die( "Error: $frg <br>". mysql_error() ) ;
$rw = mysql_fetch_object( $erg ) ;

$rechnen = $rw->zuRechnen ;
$a = raum_a ;
$b = raum_b ;
$c = raum_c ;
$t = raum_t ;

//include( '../Gutachten/Subroutines/calc_flaeche.php' ) ;

?>