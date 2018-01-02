<?php
// Gutachten
	$frage = "DELETE FROM Gutachten WHERE GutachtenID = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	$frage = "DELETE FROM Allgemeinflaeche WHERE InGutachtenID = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	$frage = "SELECT DokumentenPfad, Dokument FROM Dokumente WHERE GutachtenID = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb ) ) {
		$pfad	= '../' . $row->DokumentenPfad ;
		$dok	= $pfad . $row->Dokument ;
		unlink( $dok ) ;
		}
	rmdir( $pfad ) ;

	$frage = "DELETE FROM Dokumente WHERE GutachtenID = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	$frage = "DELETE FROM ZuAbGutachten WHERE GutachtenID = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	$frage = "DELETE FROM ZuAbGutachten WHERE GutachtenID = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
	$frage = "DELETE FROM UnterGrundSink WHERE Gut8en = $gutachten" ;
	$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

	$frag = "SELECT GebaeudeID FROM Gebaeude WHERE InGutachtenID = $gutachten" ;
	$erg = mysql_query( $frag ) OR die( "Error: $frag <br>". mysql_error() ) ;
	while( $rwx = mysql_fetch_object( $erg ) ) {
		$gebde = $rwx->GebaeudeID ;
		include( 'gebaeude.php' );
		}

	$frag = "SELECT WohnungID FROM Wohnungen WHERE InGutachten = $gutachten" ;
	$erg = mysql_query( $frag ) OR die( "Error: $frag <br>". mysql_error() ) ;
	while( $rwx = mysql_fetch_object( $erg ) ) {
		$wohng = $rwx->WohnungID ;
		include( 'wohnung.php' );
		}
?>
<!-- EOF -->