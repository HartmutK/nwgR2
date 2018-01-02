<?php
	$nixhead = false ;
	$nixfoot = false ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abf = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		if( $abgeschl ) {
			$titeldat = $ausgestellt ;
			}
		else {
			$titeldat = date( 'd.m.Y' ) ;
			}
		}

	$abf0 = "SELECT * FROM $db_user WHERE master" ;
	$erg0 = mysql_query( $abf0 )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $erg0 ) ) {
		include( '../php/get_db_user.php' ) ;
		$ma = 'MA = -' . $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
		}

	$fn = $gz . '-' . $index . '.PDF' ;

	$kopftext = 'Nutzwertgutachten: ' . $plz . ' ' . $ort . ', ' . $str ;
	if( $gutachtenid == 3 ) {
		$erstellt_mit = $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
		}
	else {
		$erstellt_mit = 'Erstellt mit parifizieren 4.0' ;
		}
	$fusstext = 'GZ: ' . $gz . '-' . $index . ' - ' . $stichtag . ' - ' . $erstellt_mit ;
	$mitarbeiter = $titel_v . ' ' . $vorname . ' ' . $name . ' ' . $titel_h ;
	$stichtag	= date( 'd.m.Y', strtotime( $stichtag ) ) ;
?>
<!-- EOF -->
