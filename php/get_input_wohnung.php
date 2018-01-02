<?php
$ingutachten			= $_SESSION[ 'gutachtenid' ] ;
$ingebaeude				= $_POST[ 'ingebaeude' ] ;
$weobjart					= $_POST[ 'weobjart' ]  ;
$whngbezeichnung	= $_POST[ 'wgnr' ] ;
$whnglage 				= $_POST[ 'wglage' ] ;
$whngzustand			= $_POST[ 'zust' ] ;
$widmung					= $_POST[ 'widm' ] ;
$besichtigung			= $_POST[ 'besichtigung' ] ;
$whngbeschreibung	= $_POST[ 'whngbeschreibung' ] ;
$miete						= $_POST[ 'miete' ] ;
if( isset( $_POST[ 'bewertet' ] ) ) { $bewertet = true ; } else { $bewertet = false ; }
if( isset( $_POST[ 'regelwhng' ] ) ) { $regelwhng = true ; } else { $regelwhng = false ; }
?>