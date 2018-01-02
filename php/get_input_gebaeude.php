<?php
$ingutachten		= $_SESSION[ 'gutachtenid' ] ;
$besichtigtam		= $_POST[ 'besichtigtam' ] ;
$gebbezeich			= $_POST[ 'gebbezeich' ] ;
$gebbeschreib		= $_POST[ 'gebbeschreib' ] ;
$baujahr				= $_POST[ 'baujahr' ] ;
$zustand				= $_POST[ 'zust' ] ;
$gebaeudeart		= $_POST[ 'gart' ] ;
$bauweise				= $_POST[ 'bauw' ] ;
$dachform				= $_POST[ 'dach' ] ;
$anzgeschosse		= $_POST[ 'anzgeschosse' ] ;
if( isset( $_POST[ 'aufzug' ] ) ) { $aufzug = true ; } else { $aufzug = false ; }
?>