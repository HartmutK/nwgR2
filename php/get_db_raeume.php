<?php
$raumid						= $row->RaumID ;
$revitid					= $row->RevitID ;
$inwohnungid			= $row->InWohnungID ;
$raumbezeichnung	= $row->Bezeichnung ;
$raumanmerkung		= $row->Anmerkung ;
$raumlage 				= $row->Lage ;
$raumart 					= $row->Raumart ;
$nebenraum				= $row->Nebenraum ;
$bewert						= $row->Bewertung ;
$bewerteinh				= $row->BewertEinheit ;
$flaeche					= $row->Flaeche ;
$nutzwert					= $row->Nutzwert ;
$nweinzel					= $row->Einzelnutzwert ;
$raumneben	 			= $row->Nebenraum ;
$reihenfolge 			= $row->Reihenfolge ;
unset( $_SESSION[ 'wohnungid' ] ) ;
$_SESSION[ 'wohnungid' ] = $inwohnungid ;
?>