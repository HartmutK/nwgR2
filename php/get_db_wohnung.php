<?php
$wohnungid				= $row->WohnungID ;
$ingutachten			= $row->InGutachten ;
$ingebaeude				= $row->InGebaeude ;
$revtbez					= $row->RevitBez ;
$weobjart					= $row->WEObjart ;
$whngbezeichnung	= $row->Bezeichnung ;
$whnglage 				= $row->Lage ;
$widmung					= $row->Widmung ;
$besichtigung			= $row->Besichtigungsdatum ;
$whngzustand			= $row->Zustand ;
$whngbeschreibung	= $row->Beschreibung ;
$bewertet					= $row->Bewertet ;
$regelwhng				= $row->Regelwohnung ;
$miete						= $row->Miete ;
$nutzflaeche			= $row->Nutzflaeche ;
$nutzwertanteil		= $row->Nutzwertanteil ;
$raumneben				= $row->Nutzwertanteil ;
$rnwwohnung				= $row->RNW ;
$rnwc							= $row->RNWcalc ;
$reihenfolge 			= $row->Reihenfolge ;

$ab1 = "SELECT Kuerzel FROM $db_lagen WHERE Lage = '$whnglage'" ;
$er1 = mysql_query( $ab1 ) OR die( "Error: $ab1 <br>". mysql_error() ) ;
while( $rwc = mysql_fetch_object( $er1 ) ) {
	$lagekurz = $rwc->Kuerzel ;
	} //while - array füllen
?>