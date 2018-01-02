<?php
	function clear_sessions( ) {
		unset( $_SESSION[ 'gutachtn' ] ) ;
		unset( $_SESSION[ 'gebaeude' ] ) ;

		unset( $_SESSION[ 'benutzer' ] ) ;
		unset( $_SESSION[ 'firmen' ] ) ;
		unset( $_SESSION[ 'userid' ] ) ;
		unset( $_SESSION[ 'firmaid' ] ) ;
		unset( $_SESSION[ 'anmelden' ] ) ;

		unset( $_SESSION[ 'wasid' ] ) ;
		unset( $_SESSION[ 'zuab' ] ) ;
		unset( $_SESSION[ 'dokumente' ] ) ;
		unset( $_SESSION[ 'dokid' ] ) ;
		unset( $_SESSION[ 'navi' ] ) ;
		unset( $_SESSION[ 'docs' ] ) ;
		unset( $_SESSION[ 'abfrage' ] ) ;
		unset( $_SESSION[ 'ids' ] ) ;
		unset( $_SESSION[ 'auswahl' ] ) ;
		unset( $_SESSION[ 'active' ] ) ;
		unset( $_SESSION[ 'idsub' ] ) ;
		unset( $_SESSION[ 'tableid' ] ) ;
		}
?>