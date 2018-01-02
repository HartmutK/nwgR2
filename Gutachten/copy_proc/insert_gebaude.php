<?php
function insert_gebaude( $newgutid, $bez ) {

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;

	$frage3	= "INSERT INTO $db_gebaeude (`GebaeudeID`, `InGutachtenID`, `RevitBez`, `BesichtigtAm`, `Bezeichnung`, `Beschreibung`, `Baujahr`, `Gebaeudeart`, `Zustand`, `Lage`, 
														`Heizung`, `Dachform`, `Bauweise`, `Besonderheiten`, `Aufzug`, `AnzGeschosse`, `Geschosshoehe`, `NwGebaeude`, `Nutzflaeche`, `Reihenfolge` )
								VALUES( NULL, '$newgutid', '$revit', '$besichtigtam', '$bez', '$gebbeschreib', '$baujahr', '$gebaeudeart', '$zustand', '$gebauedelage', 
														'$heizung', '$dachform', '$bauweise', '$besonderheiten', '$anzaufzug', '$anzgeschosse', '$geschosshoehe', '$nwgebaeude', '$nutzflaeche', '$reihenfolge' )" ;
	$ergeb3 = mysql_query( $frage3) OR die( "Error: $frage3<br>". mysql_error() ) ;
  $newgebde = mysql_insert_id() ;
	}
?>
<!-- EOF -->