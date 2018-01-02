<?php
$userid		= $row->UserID ;
$firmabez	= $row->FirmaBez ;
$anrede		= $row->Anrede ;
$titel_v	= $row->Titel_vorne ;
$titel_h	= $row->Titel_hinten ;
$name			= $row->Name ;
$vorname	= $row->Vorname ;
$jobtext1	= $row->JobText1 ;
$jobtext2	= $row->JobText2 ;
$firmaid	= $row->FirmaID ;
$email		= $row->eMail ;
$mobil		= $row->Mobil ;
$userwww	= $row->WWW ;
$login		= $row->LastLogin ;

$master 	= $row->master ;
$admin		= $row->Admin ;
$funktion	= $row->Funktion ;

$pwreset	= $row->pwReset ;
$gesperrt	= $row->Gesperrt ;

$usrfirma	= '' ;

$abf = "SELECT * FROM $db_firma WHERE FaID = '$firmaid'" ;
$erg = mysql_query( $abf )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
while( $row = mysql_fetch_object( $erg ) ) {
	include( 'get_db_firma.php' ) ;
	$usrfirma	= $row->Bezeichnung ;
	}
?>
<!-- EOF -->