<?php
	include( 'DBstring.php' ) ;

	$verbindung = mysql_connect( $srv, $usr, $pwd ) or die( "Keine Verbindung m&ouml;glich. Benutzername oder Passwort sind falsch" ) ;
	mysql_select_db( $dbname ) or die( "Die Datenbank existiert nicht." ) ;
 
// Tabellenbezeichnungen
	$db_abkuerz			= 'Abkuerzungen' ;
	$db_allgflaeche	= 'Allgemeinflaeche' ;
	$db_allgflarten	= 'AllgFlaechenArten' ;
	$db_auftraggeb	= 'Auftraggeber' ;
	$db_bauweise		= 'Bauweise' ;
	$db_dachform		= 'Dachform' ;
	$db_dokumente		= 'Dokumente' ;
	$db_einheiten		= 'Einheiten' ;
	$db_firma				= 'Firma' ;
	$db_form				= 'Form' ;
	$db_gebaeude		= 'Gebaeude' ;
	$db_gebaeudeart	= 'Gebaeudeart' ;
	$db_gutachten		= 'Gutachten' ;
	$db_gut8enzweck	= 'Gutachtenzweck' ;
	$db_txt					= 'Gutachtentexte' ;
	$db_importbim		= 'ImportData' ;
	$db_idx					= 'Indizes' ;
	$db_kastral			= 'Kastralgemeinden' ;
	$db_lpo					= 'LPO' ;
	$db_raum				= 'Raeume' ;
	$db_raumart			= 'Raumarten' ;
	$db_abmessung		= 'Raummasse' ;
	$db_raumanmerk	= 'Raum_Anmerkungen' ;
	$db_rpfleger		= 'Rechtspflegertexte' ;
	$db_system			= 'System' ;
	$db_ugsink			= 'UnterGrundSink' ;
	$db_ugsource		= 'UnterGrundSource' ;
	$db_user				= 'User' ;
	$db_weobjart		= 'WE_Objektart' ;
	$db_widmung			= 'Widmungen' ;
	$db_wohnung			= 'Wohnungen' ;
	$db_wohnungnr		= 'Wohnungsbezeichnung' ;
	$db_lagen				= 'Wohnungslagen' ;
	$db_whngzuxxx		= 'WohnungZuschlag' ;
	$db_zuabauto		= 'ZuAbAuto' ;
	$db_zuabgutacht	= 'ZuAbGutachten' ;
	$db_zuablagen		= 'ZuAbLagen' ;
	$db_zuabschlag	= 'ZuAbschlaege' ;
	$db_zuabwhng		= 'ZuAbWohnung' ;
	$db_zubehoer		= 'Zubehoer' ;
	$db_zuschlaege	= 'Zuschlaege' ;
	$db_zustand			= 'Zustand' ;
?>