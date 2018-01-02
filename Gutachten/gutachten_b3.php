<?php
	session_start( ) ;
	include( '../php/DBselect.php' ) ;
	include( '../php/Abmeld.php' ) ;

	if( isset( $_SESSION[ 'anmelden' ] ) ) {
		if( !$_SESSION[ 'anmelden' ][ 'angemeldet' ] ) {
			$ok = false ;
			}
		else {
			$ok = true ;
			}
		}
	else {
		$ok = false ;
		}

if( $ok ) {
	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'B.3' ;

	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

// Gutachtendaten holen
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	include( '../php/get_db_gutachten.php' ) ;

	if( isset( $_POST[ 'detail' ] ) ) { 
		$wohnungid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_wohnung WHERE WohnungID = $wohnungid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$wohnungid 				= $row->WohnungID ;
			$weoart						= $row->WEObjart ;
			$ingbd						= $row->InGebaeude ;
			$whngbez					= $row->Bezeichnung ;
			$whglg						= $row->Lage ;
			$widmng						= $row->Widmung ;
			$besichtig				= $row->Besichtigungsdatum ;
			$whngbeschreibung	= $row->Beschreibung ;
			$bewertet					= $row->Bewertet ;

			$merkwhnglage = $whnglage ;
			$_SESSION[ 'merkwhnglage' ] = $merkwhnglage ;

			unset( $_SESSION[ 'chngewhng' ] ) ;
			$_SESSION[ 'chngewhng' ] = $wohnungid ;
			}
		$savebuttn = true ;
		}

	elseif( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		unset( $_POST[ 'roof' ] ) ;
		$abf = "SELECT WohnungID, InGebaeude FROM $db_wohnung WHERE WohnungID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merknum = $rw->WohnungID ;
		$merkgeb = $rw->InGebaeude ;
		$abf1 = "SELECT WohnungID, Reihenfolge FROM $db_wohnung WHERE InGebaeude=$merkgeb" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->WohnungID ;
			$num 		= 1 + $rw1->Reihenfolge ;
			$abf2 = "UPDATE $db_wohnung SET Reihenfolge = '$num' WHERE WohnungID =$wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_wohnung SET Reihenfolge = '1' WHERE WohnungID =$merknum" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT InGebaeude, Reihenfolge FROM $db_wohnung WHERE WohnungID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$ingebd  = $rw->InGebaeude ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_wohnung SET Reihenfolge = $old_num WHERE InGebaeude = $ingebd AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_wohnung SET Reihenfolge = $new_num WHERE WohnungID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'rauf' ] ) ;
		}
	elseif( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT InGebaeude, Reihenfolge FROM $db_wohnung WHERE WohnungID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$ingebd  = $rw->InGebaeude ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_wohnung SET Reihenfolge = $old_num WHERE InGebaeude = $ingebd AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_wohnung SET Reihenfolge = $new_num WHERE WohnungID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'runter' ] ) ;
		}
	elseif( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		unset( $_POST[ 'floor' ] ) ;
		$abf = "SELECT WohnungID, InGebaeude, Reihenfolge FROM $db_wohnung WHERE WohnungID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merknum		= $rw->WohnungID ;
		$merkgeb		= $rw->InGebaeude ;
		$merkfolge	= $rw->Reihenfolge ;
		$abf1 = "SELECT WohnungID, Reihenfolge FROM $db_wohnung WHERE InGebaeude=$merkgeb AND Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->WohnungID ;
			$num 		= $rw1->Reihenfolge - 1 ;
			$abf2 = "UPDATE $db_wohnung SET Reihenfolge = '$num' WHERE WohnungID =$wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$num 	= $num + 1 ;
		$abf2 = "UPDATE $db_wohnung SET Reihenfolge = '$num' WHERE WohnungID =$merknum" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}

	elseif( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		unset( $_SESSION[ 'chngewhng' ] ) ;
		$savebuttn = false ;
		}

	elseif( isset( $_POST[ 'save' ] ) ) {
		$weoart						= $_POST[ 'weoart' ] ;
		$ingbd						= $_POST[ 'ingbd' ] ;
		$whglg						= $_POST[ 'whglg' ] ;
		$wgnr							= $_POST[ 'wgnr' ] ;
		$widmng						= $_POST[ 'widmng' ] ;
		$besichtig				= $_POST[ 'besichtig' ] ;
		$whngbeschreibung	= $_POST[ 'whngbeschreibung' ] ;
		if( isset( $_POST[ 'bewertet' ] ) ) { $bewertet = true ; } else { $bewertet = false ; }
		if( $besichtigung == '' ) {	$besichtigung = date( "Y.m.d" ) ; }

		if( $ingbd == '-' ) {
			$errortxt = 'Objekt fehlt' ;
			}
		else {
			$abfrage = "SELECT GebaeudeID FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid AND Bezeichnung = '$ingbd'" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			$rw1 = mysql_fetch_object( $ergebnis ) ;
			$ingebaud = $rw1->GebaeudeID ;

			if( isset( $_SESSION[ 'chngewhng' ] ) ) {
				$wohnungid = $_SESSION[ 'chngewhng' ] ;
				unset( $_SESSION[ 'chngewhng' ] ) ;
				$abfrage = "UPDATE $db_wohnung 
												SET WEObjart = '$weoart', Lage = '$whglg', InGebaeude = '$ingebaud', Bezeichnung = '$wgnr', Widmung = '$widmng',  Beschreibung = '$whngbeschreibung', 
														Besichtigungsdatum = '$besichtig', Bewertet = '$bewertet'
												WHERE WohnungID = $wohnungid" ;
				$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
				$merkwhnglage = $_SESSION[ 'merkwhnglage' ] ;
				if( $merkwhnglage != $whnglage ) {
					$abfrage = "UPDATE $db_raum SET Lage = '$whnglage' WHERE Lage = '$merkwhnglage' AND InWohnungID = $wohnungid" ;
					$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
					}
				$errortxt = 'Ge&auml;ndert' ;
				}
			else {
				if( $ingebaud != '' ) {
					$abf = "SELECT MAX( Reihenfolge ) AS MaxNr FROM $db_wohnung WHERE InGebaeude = $ingebaud" ;
					$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
					$rw = mysql_fetch_object( $erg ) ;
					$next_doc = 1 + $rw->MaxNr ;
					}
				else {
					$next_doc = 1 ;
					}

				$abfrage = "INSERT INTO $db_wohnung (`WohnungID`, `InGutachten`, `InGebaeude`, `WEObjart`, `Bezeichnung`, `Lage`, `Widmung`, `Besichtigungsdatum`, 
																							`Zustand`, `Beschreibung`, `Bewertet`, `Regelwohnung`, `Reihenfolge` )
											VALUES( NULL, '$gutachtenid', '$ingbd', '$weoart', '$wgnr', '$whglg', '$widmng', '$besichtig', 
															'', '$whngbeschreibung', '$bewertet', false, '$next_doc' )" ;
				$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;

				$rs = mysql_query("SELECT LAST_INSERT_ID()");
				$wohnungid = mysql_result($rs,0) ;

				$merkwhnglage = $_SESSION[ 'merkwhnglage' ] ;

				if( $merkwhnglage != $whnglage ) {
// Alte Einträge löschen
					$abf1 = "SELECT LageID FROM $db_lagen WHERE Kuerzel = '$merkwhnglage'" ;
					$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf <br>". mysql_error() ) ;
					while( $rw1 = mysql_fetch_object( $erg1 ) ) {
						$lagenid = $rw1->LageID ;
						$abf2 = "SELECT AutoZuAb FROM $db_zuabauto WHERE AutoLage = '$lagenid'" ;
						$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
						while( $rw2 = mysql_fetch_object( $erg2 ) ) {
							$autzuabid = $rw2->AutoZuAb ;
							$abf3 = "DELETE FROM $db_zuabwhng WHERE ZuAbWhng = '$wohnungid' AND ZuAb_ZuAb = '$autzuabid'" ;
							$erg3 = mysql_query( $abf3 )  OR die( "Error: $abf3 <br>". mysql_error() ) ;
							}
						}
// Lageabhängige Einträge einfügen
					$abf1 = "SELECT LageID FROM $db_lagen WHERE Kuerzel = '$whnglage'" ;
					$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf <br>". mysql_error() ) ;
					while( $rw1 = mysql_fetch_object( $erg1 ) ) {
						$lagenid = $rw1->LageID ;
						$abf2 = "SELECT AutoZuAb FROM $db_zuabauto WHERE AutoLage = '$lagenid'" ;
						$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
						while( $rw2 = mysql_fetch_object( $erg2 ) ) {
							$zuabid = $rw2->AutoZuAb ;
							$abf3 = "SELECT * FROM $db_zuabschlag WHERE ZuAbID = '$zuabid'" ;
							$erg3 = mysql_query( $abf3 )  OR die( "Error: $abf3 <br>". mysql_error() ) ;
							$row = mysql_fetch_object( $erg3 ) ;
							include( "../php/get_db_zuabschlag.php" ) ;
							$abf4	= "INSERT INTO $db_zuabwhng (`ZuAbWhngID`, `Gut8en`, `ZuAbGbde`, `ZuAbWhng`, `ZuAb_ZuAb`, `Gruppe`, `ZuAbKurz`, `ZuAbschlag`, `Min`, `Dflt`, `Max`, `Einheit` ) 
														VALUES( NULL, '$tableid', '$gebdeid', '$wohnungid', '$zuabid', '$zuab_gruppe', '$zuab_kurz', '$zuab_komment', '$zuab_min', '$zuab_wieviel', '$zuab_max', '$einheit' )" ;
							$erg4 = mysql_query( $abf4 )  OR die( "Error: $abf4 <br>". mysql_error() ) ;
							}
						}
					unset( $_SESSION[ 'merkwhnglage' ] ) ;
					$whngbeschreibung	= '' ;
					} // $merkwhnglage != $whnglage

				$weobjart					= '' ;
				$ingebd						= '' ;
				$whnglage 				= '' ;
				$whngbezeichnung	= '' ;
				$widm							= '' ;
				$whngbeschreibung	= '' ;
				$besichtigung			= '' ;
				$bewertet 				= false ; 

				$errortxt = 'Gespeichert' ;
				}
			$savebuttn = false ;
			}
		}
	else {
		$savebuttn	= false ;
		}

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;


//--------------------------------------------------------------------------------------
//---------------Make Liste ----------------------------------------

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$num = 0 ;
	$abfrag1 = "SELECT WohnungID FROM $db_wohnung WHERE InGutachten=$gutachtenid ORDER BY Reihenfolge" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	while( $row1 = mysql_fetch_object( $ergebn1 ) ) {
		$num++ ;
		$wohnnr = $row1->WohnungID ;
		$abfrag2 = "UPDATE $db_wohnung SET Reihenfolge = '$num' WHERE WohnungID =$wohnnr" ;
		$ergebn2 = mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		}

	unset( $wohnungen ) ;
	$abfrag1 = "SELECT * FROM $db_wohnung WHERE InGutachten=$gutachtenid ORDER BY Reihenfolge" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebn1 ) ) {
		include( "../php/get_db_wohnung.php" ) ;
		if( $ingebaeude > 0 ) {
			$abf = "SELECT Bezeichnung FROM $db_gebaeude WHERE GebaeudeID = $ingebaeude" ;
			$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
			$rw1 = mysql_fetch_object( $erg ) ;
			$gebaud = $rw1->Bezeichnung ;
			}
		else {
			$gebaud = '' ;
			}
		$wohnungen [ ] = array( 'we_objid'=>$wohnungid, 'gut8'=>$ingutachten, 'gebd'=>$gebaud, 'weartobj'=>$weobjart, 'lage'=>$whnglage, 
														'nummer'=>$whngbezeichnung, 'widmg'=>$widmung, 'anmerk'=>$whngbeschreibung, 'besichtigt'=>$besichtigung, 'bewertet'=>$bewertet ) ;
		}

// ----------------------------------------------------------------------------------

	include( "../php/head.php" ) ;
	?>

		<div class="clear"></div>
		<div id="mainb3">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainb3_oben">
					<?php
					if( !$abgeschl ) {
						?>
						<div class="trin">
							<div class="col1">
								<?php	// WE-Objekt
								echo '<select name="weoart" size="1" class="col1">'; 
								$abfrage = "SELECT WEArt FROM $db_weobjart WHERE Aktiv ORDER BY Reihenfolge" ;
								$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg ) ) {
									$art = $row->WEArt ;
									if( $art == $weoart ) { echo '<option selected value="' . $art . '">' . $art . '</option>'; }
									else { 	echo '<option value="' . $art . '">' . $art . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col2">
								<?php	// Objekt
								echo '<select name="ingbd" size="1" class="col2">'; 
								$abfrage = "SELECT Bezeichnung FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid ORDER BY Bezeichnung" ;
								$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg ) ) {
									$bezch = $row->Bezeichnung ;
									if( $bezch == $ingbd ) { 	echo '<option selected value="' . $bezch . '">' . $bezch . '</option>'; }
									else { 	echo '<option value="' . $bezch . '">' . $bezch . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col3">
								<?php	// Lage
								echo '<select name="whglg" size="1" class="col3">'; 
								$abfrage = "SELECT Kuerzel FROM $db_lagen ORDER BY Reihenfolge" ;
								$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg ) ) {
									$wglage = $row->Kuerzel ;
									if( $wglage == $whglg ) { 	echo '<option selected value="' . $wglage . '">' . $wglage . '</option>'; }
									else { 	echo '<option value="' . $wglage . '">' . $wglage . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col4">
								<?php	// Wohnung
								echo '<select name="wgnr" size="1" class="col4">'; 
								$abfrage = "SELECT Wohnungnr FROM $db_wohnungnr ORDER BY Wohnungnr" ;
								$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg ) ) {
									$wgnr = $row->Wohnungnr ;
									if( $wgnr == $whngbez) { 	echo '<option selected value="' . $wgnr . '">' . $wgnr . '</option>'; }
									else { 	echo '<option value="' . $wgnr . '">' . $wgnr . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col5">
								<?php	// Widmung
								echo '<select name="widmng" size="1" class="col5">'; 
								$abf2 = "SELECT Widmung FROM $db_widmung WHERE Aktiv ORDER BY Reihenfolge" ;
								$erg2 = mysql_query( $abf2 ) OR die( "Error: $abf2 <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg2 ) ) {
									$widm = $row->Widmung ;
									if( $widm == $widmng ) { 	echo '<option selected value="' . $widm . '">' . $widm . '</option>'; }
									else { 	echo '<option value="' . $widm . '">' . $widm . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col6"><input type="date" name="besichtig" class="col6" value="<?php echo $besichtig ; ?>"></div>
							<div class="col7"><input type=checkbox <?php if( $bewertet == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="bewertet"/></div>
							<div class="col_buttons">
								<button type="submit" Name="save" class="okay_buttn"></button>
								<?php
								if( $savebuttn ) {
									?>
									<button type="submit" name="bck" class="back_buttn"></button>
									<?php
									}
								?>
								<div class="col_buttons"><?php echo $errortxt ; ?></div>
								</div> <!-- buttns -->
							</div> <!-- b5tabrow -->
						<?php
						}

					$merkwhng = '' ;

					?>

					<div class="clear"></div>
					<div class="trhd">
						<div class="col1">WE-Objekt</div>
						<div class="col2">Objekt</div>
						<div class="col3">Lage</div>
						<div class="col4">Nummer</div>
						<div class="col5">Widmung</div>
						<div class="col6">Besichtigt</div>
						<div class="col7">Bewertet</div>
						</div>
					</div> <!-- mainb3_oben -->

				<div class="clear"></div>
				<div id="mainb3_unten">

					<?php
					$i = 0 ;
					$anz = count( $wohnungen ) ;
					while( $i < $anz ) {
						$we_objid		= $wohnungen [ $i ][ 'we_objid' ] ;
						$gut8				= $wohnungen [ $i ][ 'gut8' ] ;
						$gebd				= $wohnungen [ $i ][ 'gebd' ] ;
						$wohngid		= $wohnungen [ $i ][ 'we_id' ] ;
						$weartobj		= $wohnungen [ $i ][ 'weartobj' ] ;
						$lge				= $wohnungen [ $i ][ 'lage' ] ;
						$nummer			= $wohnungen [ $i ][ 'nummer' ] ;
						$widmg			= $wohnungen [ $i ][ 'widmg' ] ;
						$besichtigt	= $wohnungen [ $i ][ 'besichtigt' ] ;
						$bewert 		= $wohnungen [ $i ][ 'bewertet' ] ;

						if( $besichtigt != '0000-00-00' ) {
							$besichtigt	= date( "Y.m.d", strtotime( $besichtigt ) ) ;
							}
						else {
							$besichtigt	= '-' ;
							}
						if( $weartobj == '' ) { $weartobj = '-' ; }
						if( $widmg == '' ) { $widmg = '-' ; }
						if( $lge == '' ) { $lge = '-' ; }
						if( $gebd == '' ) { $gebd = '-' ; }
						?>
						<div class="tr">
							<div class="col1"><?php echo $weartobj ; ?></div>
							<div class="col2"><?php echo $gebd ; ?></div>
							<div class="col3"><?php echo $lge ; ?></div>
							<div class="col4"><?php echo $nummer; ?></div>
							<div class="col5"><?php echo $widmg ; ?></div>
							<div class="col6"><?php echo $besichtigt ; ?></div>
							<div class="col7"><input type=checkbox disabled <?php if( $bewert == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?>/></div>
							<div class="col_buttons">
								<?php
								if( !$abgeschl ) {
									?>
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $we_objid ; ?>"></button>
									<a href="copy_proc/cpy_wohnung.php?wohnung=<?php echo $we_objid ; ?>" class="copy_buttn"></a>
									<a href="delete_includes/del_wohnung.php?wohnung=<?php echo $we_objid ; ?>" class="delete_buttn"></a>
									<?php
									if( $i > 0 ) {
										?>
										<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $we_objid ; ?>"></button>
										<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $we_objid ; ?>"></button>
										<?php
										}
									if( $i < $anz - 1 ) {
										if( $i == 0 ) {
											?>
											<button type="submit" name="runter"	class="runter_buttn_solo" value="<?php echo $we_objid ; ?>"></button>
											<?php
											}
										else {
											?>
											<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $we_objid ; ?>"></button>
											<?php
											}
										?>
										<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $we_objid ; ?>"></button>
										<?php
										}
									}
									?>
								</div>
							</div>
						<?php
						$i++ ;
						}
						?>
					</div> <!-- mainb3_unten -->
				</form><!-- main -->
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->