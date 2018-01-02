<?php
	function set_angemeldet( $id, $mail, $adm ) {
		$_SESSION[ 'anmelden' ] = array() ;
		$anmelden = array() ;
		$anmelden = array( 'angemeldet'=>true, 'id'=>$id, 'mail'=>$mail, 'adm'=>$adm ) ;
		$_SESSION[ 'anmelden' ] = $anmelden ;
		if( $adm ) {
			$dsp = 20 ;		// Admin
			}
		else {
			$dsp = 40 ;
			}
		return( $dsp ) ;
		}
?>