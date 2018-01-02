<?php
//$inwohung					= $_SESSION[ 'wohnung' ] ;
$raumbezeichnung	= $_POST[ 'raumbezeichnung' ] ;
$raumart					= $_POST[ 'rmart' ] ;
$raumlage					= $_POST[ 'rmlage' ] ;
$flaeche					= $_POST[ 'flaeche' ] ;
if( isset( $_POST[ 'nebenraum' ] ) ) { $nebenraum = true ; } else { $nebenraum = false ; }
?>