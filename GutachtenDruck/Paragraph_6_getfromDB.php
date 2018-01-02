<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	$abfrage = "SELECT * FROM Urkundenverzeichnis WHERE ID = '8'";       
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;

	$wer = $row->Wer ;
	$inhalt = $row->Inhalt ;


echo 'wer = ' . $wer ;
echo 'inh = ' . $inhalt ;


	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="' . $inhalt . '"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	ob_clean() ;
	ob_flush () ;
	@readfile( $inhalt ) ;
?>
<!-- EOF -->