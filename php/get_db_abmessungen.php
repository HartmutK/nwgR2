<?php
$massid			= $row->MassID ;
$vonraumid	= $row->vonRaumID ;
$raum_a			= $row->A ;
$raum_b			= $row->B ;
$raum_c			= $row->C ;
$raum_t			= $row->T ;
$form				= $row->Form ;
$addieren		= $row->Addieren ;
$formid			= 0 ;
$formel			= '' ;
$rechne			= '' ;

$abfr = "SELECT * FROM $db_form WHERE Form = '$form'" ;
$erg = mysql_query( $abfr )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
while( $row = mysql_fetch_object( $erg ) ) {
	$formid	= $row->FormID ;
	$formel	= $row->Formel ;
	$rechne	= $row->ZuRechnen ;
	}
?>