<?php
$firmabez	= $_POST[ 'firmabez' ] ;
$anrede		= $_POST[ 'anrede' ] ;
$titel_v	= $_POST[ 'titel_v' ] ;
$name			= $_POST[ 'name' ] ;
$vorname	= $_POST[ 'vorname' ] ;
$titel_h	= $_POST[ 'titel_h' ] ;
$jobtext1	= $_POST[ 'jobtext1' ] ;
$jobtext2	= $_POST[ 'jobtext2' ] ;
$email		= $_POST[ 'email' ] ;
$email		= $_POST[ 'email' ] ;
$userwww	= $_POST[ 'userwww' ] ;
$funktion	= $_POST[ 'funktion' ] ;
if( isset( $_POST[ 'admin' ] ) ) { $admin = true ; } else { $admin = false ; }
if( isset( $_POST[ 'master' ] ) ) { $master = true ; } else { $master = false ; }
if( isset( $_POST[ 'pwreset' ] ) ) { $pwreset = true ; } else { $pwreset = false ; }
if( isset( $_POST[ 'gesperrt' ] ) ) { $gesperrt = true ; } else { $gesperrt = false ; }
?>
<!-- EOF -->