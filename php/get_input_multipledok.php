<?php
$dokid = $_SESSION[ 'dokid' ] ;
$bezeichng = $_POST[ 'bezeichng' ] ;
$beschreibung = $_POST[ 'beschreibung' ] ;
$reihenfolge = $_POST[ 'reihenfolge' ] ;

if( isset( $_POST[ 'seite1' ] ) ) { $seite1 = true ; } else { $seite1 = false ; }
if( isset( $_POST[ 'titelbild' ] ) ) { $titelbild = true ; } else { $titelbild = false ; }
if( isset( $_POST[ 'drucken' ] ) ) { $drucken = true ; } else { $drucken = false ; }
if( isset( $_POST[ 'grundbuch' ] ) ) { $grundbuch = true ; } else { $grundbuch = false ; }
if( isset( $_POST[ 'gba_s1' ] ) ) { $gba_s1 = true ; } else { $gba_s1 = false ; }
if( isset( $_POST[ 'bild' ] ) ) { $bild = true ; } else { $bild = false ; }
if( isset( $_POST[ 'grundriss' ] ) ) { $grundriss = true ; } else { $grundriss = false ; }
if( isset( $_POST[ 'lageplan' ] ) ) { $lageplan = true ; } else { $lageplan = false ; }

$weg = '../upload/' . $gutachtenid . '-' ;
$dok = $_FILES['file']['name'] ;

if( $dok != '' ) {
	$upload_folder = '../upload/' ; //Das Upload-Verzeichnis
	$fn = $gutachtenid . '-' . pathinfo( $_FILES['file']['name'], PATHINFO_FILENAME ) ;
	$extension = strtolower( pathinfo( $_FILES['file']['name'], PATHINFO_EXTENSION ) ) ;
	}
	
	//Überprüfung der Dateigröße
	$max_size = 1000*1024; // 1MB
	$sze = $_FILES['file']['size'] ;
	if( $sze > $max_size ) {
		$errortxt = 'Bitte keine Dateien gr&ouml;ßer als 1MB hochladen.' ;
		$no_err = false ;
		}
	else {
		$no_err = true ;
		}

	//Überprüfung dass das Bild keine Fehler enthält
	if( function_exists( 'exif_imagetype' ) ) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
		$allowed_types = array( IMAGETYPE_PNG, IMAGETYPE_JPG, IMAGETYPE_JPEG, IMAGETYPE_GIF ) ;
		$detected_type = exif_imagetype( $_FILES['file']['tmp_name'] ) ;
		if( !in_array( $detected_type, $allowed_types ) ) {
			$errortxt = 'Nur der Upload von Bilddateien im Format png, jpg, jpeg oder gif ist gestattet.' ;
			$no_err = false ;
			}
		else {
			$no_err = true ;
			}
		}

	$pfad = $weg . $dok ;

	//Alles okay
	if( $no_err ) {
		move_uploaded_file( $_FILES['file']['tmp_name'], $pfad ) ;
		$errortxt = 'Bild erfolgreich hochgeladen.' ;
		}

?>
<!-- EOF -->