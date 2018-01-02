<?php
	session_start( ) ;
	include( '../php/DBselect.php' ) ;
	include( '../php/Abmeld.php' ) ;
	include( 'Subroutines/zuschlag_zubehoer.php' ) ;

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
	$_SESSION[ 'navi' ] = 'B.4' ;

	$bereich = 'Nutzfl&auml;chenberechnung ' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = '$gutachtenid'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( isset( $_POST[ 'search' ] ) ) {
		$wohnung = $_POST[ 'wohnungswahl' ] ;

		if( isset( $_SESSION['m_id'] ) ) {
			$m_id =  $_SESSION[ 'm_id' ] ;
			if( $m_id != $wohnung ) {
				$m_reihe =  $_SESSION[ 'm_reihe' ] ;
				$abf = "UPDATE $db_wohnung SET Reihenfolge = $m_reihe WHERE WohnungID = $m_id" ;
				$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
				}
			}

		$abf2 = "SELECT WohnungID, InGebaeude, Reihenfolge FROM $db_wohnung WHERE WohnungID = $wohnung" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		$rw2 = mysql_fetch_object( $erg2 ) ;
		$m_id			= $rw2->WohnungID ;
		$m_geb		= $rw2->InGebaeude ;
		$m_reihe	= $rw2->Reihenfolge ;
		$_SESSION[ 'm_id' ] = $m_id ;
		$_SESSION[ 'm_reihe' ] = $m_reihe ;
//		$abf = "SELECT MIN( Reihenfolge ) AS MinNr FROM $db_wohnung WHERE InGebaeude = $m_geb" ;
//		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
//		$rw = mysql_fetch_object( $erg ) ;
//		$ontop = 1 + $rw->MinNr ;
		$ontop = 0 ;

		$abf = "UPDATE $db_wohnung SET Reihenfolge = $ontop WHERE WohnungID = $m_id" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		} // search

	elseif( isset( $_POST[ 'searchreset' ] ) ) {
		$m_id =  $_SESSION[ 'm_id' ] ;
		$m_reihe =  $_SESSION[ 'm_reihe' ] ;
		if( $m_id != '' ) {
			$abf = "UPDATE $db_wohnung SET Reihenfolge = $m_reihe WHERE WohnungID = $m_id" ;
			$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
			}
		unset( $_SESSION[ 'm_id' ] ) ;
		unset( $_SESSION[ 'm_reihe' ] ) ;
		unset( $_POST[ 'searchreset' ] ) ;
		}

	elseif( isset( $_POST[ 'detail' ] ) ) {
		$dokid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_raum WHERE RaumID = $dokid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $ergebnis ) ) {
			$gebid				= $rw1->Gebaeude ;
			$whngid				= $rw1->WohnungID ;
			$raumid				= $rw1->RaumID ;
			$raum_art			= $rw1->Raumart ;
			$raum_anmerk	= $rw1->Anmerkng ;
			$raum_lage		= $rw1->Lage ;
			$merk_lage		= $raum_lage ;
			$nebenr				= $rw1->Nebenraum ;
			$flche				= $rw1->Flaeche ;
			$bewert 			= $rw1->Bewertung ;
			$einht				= $rw1->BewertEinheit ;
			}
		unset( $_SESSION[ 'raumid' ] ) ;
		$_SESSION[ 'raumid' ] = $raumid ;
		unset( $_SESSION[ 'detail' ] ) ;
		$chgbuttn 	= true ;
		}

	elseif( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		unset( $_POST[ 'roof' ] ) ;
		$abf = "SELECT RaumID, InWohnungID FROM $db_raum WHERE RaumID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merknum = $rw->RaumID ;
		$merkgeb = $rw->InWohnungID ;
		$abf1 = "SELECT RaumID, Reihenfolge FROM $db_raum WHERE InWohnungID =$merkgeb" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->RaumID ;
			$num 		= 1 + $rw1->Reihenfolge ;
			$abf2 = "UPDATE $db_raum SET Reihenfolge = '$num' WHERE RaumID=$wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_raum SET Reihenfolge = '1' WHERE RaumID=$merknum" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT InWohnungID, Reihenfolge FROM $db_raum WHERE RaumID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$ingebd  = $rw->InWohnungID ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_raum SET Reihenfolge = $old_num WHERE InWohnungID = $ingebd AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_raum SET Reihenfolge = $new_num WHERE RaumID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT InWohnungID, Reihenfolge FROM $db_raum WHERE RaumID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$ingebd  = $rw->InWohnungID ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_raum SET Reihenfolge = $old_num WHERE InWohnungID = $ingebd AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_raum SET Reihenfolge = $new_num WHERE RaumID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		unset( $_POST[ 'floor' ] ) ;
		$abf = "SELECT RaumID, InWohnungID, Reihenfolge FROM $db_raum WHERE RaumID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merknum		= $rw->RaumID ;
		$merkgeb		= $rw->InWohnungID ;
		$merkfolge	= $rw->Reihenfolge ;
		$abf1 = "SELECT RaumID, Reihenfolge FROM $db_raum WHERE InWohnungID=$merkgeb AND Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->RaumID ;
			$num 		= $rw1->Reihenfolge - 1 ;
			$abf2 = "UPDATE $db_raum SET Reihenfolge = '$num' WHERE RaumID=$wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$num 	= $num + 1 ;
		$abf2 = "UPDATE $db_raum SET Reihenfolge = '$num' WHERE RaumID=$merknum" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		unset( $_SESSION[ 'chngewhng' ] ) ;
		$chgbuttn = false ;
		}
	elseif( isset( $_POST[ 'save' ] ) ) {
		$merk_rmart		= $raum_art ;
		$raum_art			= $_POST[ 'raum_art' ] ;
		$raum_anmerk	= $_POST[ 'raum_anmerk' ] ;
		$raum_lage		= $_POST[ 'raum_lage' ] ;
		$neben				= $_POST[ 'nebenr' ] ;
		$flche				= $_POST[ 'flche' ] ;
		$wohnungid		= $_POST[ 'wohnungswahl' ] ;

		$abf = "SELECT Raumart, Bewertung, Einheit FROM $db_raumart WHERE Raumart = '$raum_art'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$rmart = $rw->Raumart ;
			if( $rmart != $merk_rmart ) {
				$bewert = $rw->Bewertung ;
				$einht	= $rw->Einheit ;
				}
			}

		if( isset( $_POST[ 'nebenraum' ] ) ) { $nebenraum = true ; } else { $nebenraum = false ; }

		if( isset( $_SESSION[ 'raumid' ] ) ) {
			$raumid = $_SESSION[ 'raumid' ] ;
			$abfrage = "UPDATE $db_raum SET Raumart = '$raum_art', Anmerkung = '$raum_anmerk', Lage = '$raum_lage', Nebenraum = '$nebenr', Flaeche = '$flche', 
																				Bewertung = '$bewert', BewertEinheit = '$einht'
																				WHERE RaumID = $raumid" ;
			$errortxt = 'Ge&auml;ndert' ;
			$chgbuttn = false ;
			}
		else {
			$abf = "SELECT MAX( Reihenfolge ) AS MaxNr FROM $db_raum WHERE InWohnungID = $wohnungid" ;
			$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
			$rw = mysql_fetch_object( $erg ) ;
			$next_doc = 1 + $rw->MaxNr ;

			$abfrage	= "INSERT INTO $db_raum (`RaumID`, `InWohnungID`, `Raumart`, `Anmerkung`, `Lage`, `Nebenraum`, `Flaeche`, `Bewertung`, `BewertEinheit`, `Reihenfolge` )
										VALUES ( NULL, '$wohnungid', '$raum_art', '$raum_anmerk', '$raum_lage', '$nebenr', '$flche', '$bewert', '$einht', '$next_doc' )" ;
			$errortxt = 'Gespeichert' ;
			}

		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		unset( $_SESSION[ 'raumid' ] ) ;
		unset( $_SESSION[ 'save' ] ) ;
		$merk_lage		= $raum_lage ;
		$merk_wohnung	= $wohnungid ;
		$raum_art			= '' ;
		$raum_anmerk	= '' ;
		$raum_lage		= '' ;
		$nebenr				= false ;
		$flche 				= 0 ;
		}
	else {
		$chgbuttn 	= false ;
		}

//--------------------------------------------------------------------------------------
//---------------Make Liste ----------------------------------------

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$g_bez	= '' ;
	$merk_geb = 0 ;
	unset( $table_whng ) ;

	$num = 0 ;
	$abf2 = "SELECT WohnungID, InGebaeude, WEObjart, Bezeichnung, Lage, Widmung, Regelwohnung FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
	$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
	while( $rw2 = mysql_fetch_object( $erg2 ) ) {
		$num++ ;
		$w_id		= $rw2->WohnungID ;
		$abfrag2 = "UPDATE $db_wohnung SET Reihenfolge = '$num' WHERE WohnungID =$w_id" ;
		mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		$g_id		= $rw2->InGebaeude ;
		$w_art	= $rw2->WEObjart ;
		$w_bez	= $rw2->Bezeichnung ;
		$w_lage	= $rw2->Lage ;
		$w_widm	= $rw2->Widmung ;
		$w_rw		= $rw2->Regelwohnung ;
		$abf1 = "SELECT DISTINCT COUNT( Bezeichnung ) AS AnzGeb FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		$rw1 = mysql_fetch_object( $erg1 ) ;
		$anzgeb = $rw1->AnzGeb ;
		if( $anzgeb > 1 ) {
			$merk_geb = $g_id ;
			$abf1 = "SELECT Bezeichnung FROM $db_gebaeude WHERE GebaeudeID = $g_id" ;
			$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
			$rw1 = mysql_fetch_object( $erg1 ) ;
			$g_bez	= $rw1->Bezeichnung ;
			}
		else {
			$g_bez	= '-' ;
			}
		$table_whng [ ] = array( 'g_id'=>$g_id, 'g_bez'=>$g_bez, 'w_id'=>$w_id, 'w_art'=>$w_art, 'w_bez'=>$w_bez, 'w_lage'=>$w_lage, 'w_widm'=>$w_widm, 'w_rw'=>$w_rw ) ;
		} // Wohnung

//--------------------------------------------------------------------------------------

	unset( $_POST[ 'detail' ] ) ;
	unset( $_POST[ 'save' ] ) ;

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= ''  ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

?>

<?php include( "../php/head.php" ) ; ?>

		<div class="clear"></div>
		<div id="mainb4">
	
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainb4_oben">
					<div class="clear"></div>
					<div class="tr">
						<?php
						$wohnungswahl = $wohnung	;
						echo '<select name="wohnungswahl" size="1" class="sp1">'; 
						$abfrage = "SELECT WohnungID, Bezeichnung , Lage, Widmung FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
						$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						while( $row = mysql_fetch_object( $ergeb ) ) {
							$eidi = $row->WohnungID ;
							$bez = $row->Bezeichnung ;
							$wng = $bez . ', ' . $row->Lage ;
							if( $eidi == $wohnungswahl ) { 	echo '<option selected value="' . $eidi . '">' . $wng . '</option>'; }
							else { 	echo '<option value="' . $eidi . '">' . $wng . '</option>'; }
							} //while - array füllen
						echo'</select>';
						?>
						<div class="col_buttons">
							<button type="submit" name="search" class="search_buttn" value="<?php echo $eidi ; ?>"></button>
							<button type="submit" name="searchreset" class="back_buttn" value="<?php echo $eidi ; ?>"></button>
							</div> <!-- buttns -->
						</div> <!-- b5tabrw -->

					<?php
					if( !$abgeschl ) {
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="sp1a">
								<?php
								echo '<select name="raum_art" size="1" class="sp1">'; 
								$abfrage = "SELECT Raumart FROM $db_raumart WHERE Aktiv ORDER BY Raumart" ;
								$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $ergeb ) ) {
									$rmart = $row->Raumart ;
									if( $rmart == $raum_art ) { 	echo '<option selected value="' . $rmart . '">' . $rmart . '</option>'; }
									else { 	echo '<option value="' . $rmart . '">' . $rmart . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="sp2a">
								<?php
								echo '<select name="raum_anmerk" size="1" class="sp2">'; 
								$abfrage = "SELECT RaumAnmTxt FROM $db_raumanmerk WHERE Aktiv ORDER BY RaumAnmTxt" ;
								$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $ergeb ) ) {
									$anmerk = $row->RaumAnmTxt ;
									if( $anmerk == $raum_anmerk ) { 	echo '<option selected value="' . $anmerk . '">' . $anmerk . '</option>'; }
									else { 	echo '<option value="' . $anmerk . '">' . $anmerk . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="sp3a">
								<?php
									$raum_lage = $merk_lage ;
									echo '<select name="raum_lage" size="1" class="sp3">'; 
									$abfrage = "SELECT Kuerzel FROM $db_lagen ORDER BY Lage" ;
									$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
									while( $row = mysql_fetch_object( $ergeb ) ) {
										$rmlage = $row->Kuerzel ;
										if( $rmlage == $raum_lage ) { 	echo '<option selected value="' . $rmlage . '">' . $rmlage . '</option>'; }
										else { 	echo '<option value="' . $rmlage . '">' . $rmlage . '</option>'; }
										} //while - array füllen
									echo'</select>';
								?>
								</div>
							<div class="sp4"><input type=checkbox name="nebenr" <?php if( $nebenr == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> value="<?php echo $nebenr ; ?>"></div>
							<div class="sp7_1"><input type="number" name="flche" class="sp7" step="0.01" value="<?php echo $flche ; ?>"></div>
	
							<div class="col_buttons85">
								<button type="submit" name="save" class="okay_buttn" value="<?php echo $wid ; ?>"></button>
								<?php
								if( $chgbuttn ) {
									?>
									<button type="submit" name="bck" class="back_buttn"></button>
									<?php
									}
								?>
								<div class="etxt"><?php echo $errortxt ; ?></div>
								</div> <!-- buttns -->
							</div> <!-- tr -->
						<div class="clear"></div>
						<div class="trhd">
							<div class="sp1a">R&auml;ume</div>
							<div class="sp2a">Anmerkungen</div>
							<div class="sp3a">Lage</div>
							<div class="sp4">Nebenr.</div>
							<div class="sp5a">Formel</div>
							<div class="sp5a">Berechnung</div>
							<div class="sp7">EFL in m&sup2;</div>
							<div class="sp7">GFL in m&sup2;</div>
							<div class="sp7">NFL in m&sup2;</div>
							</div> <!-- trhd -->
						<?php
						}
						?>
						</div> <!-- main_oben -->
				<div id="mainb4_unten">
					<?php
					$merk_raumid = 0 ;
					$merk_whngid = 0 ;
					$gfl = 0 ;
					$nfl = 0 ;

					$i = 0 ;
					$anz = count( $table_whng ) ;
					while( $i < $anz ) {
						$idw			= $table_whng [ $i ][ 'w_id' ] ;
						$gebaude	= $table_whng [ $i ][ 'g_bez' ] ;
						$whngbez	= $table_whng [ $i ][ 'w_bez' ] ;
						$whngart	= $table_whng [ $i ][ 'w_art' ] ;
						$whnglage = $table_whng [ $i ][ 'w_lage' ] ;
						$widmung	= $table_whng [ $i ][ 'w_widm' ] ;
						$rwhng		= $table_whng [ $i ][ 'w_rw' ] ;
						$weo_txt = '' ;

						if( $merk_whngid != $idw ) {
							$abf = "UPDATE $db_wohnung SET Nutzflaeche = $nfl WHERE WohnungID = $merk_whngid" ;
							$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
							$nfl = number_format( round( $nfl, 2 ), 2, ',', '.' ) ;
							if( $i>0 AND $nfl!=0 ) {
								?>
								<div class="sp9"><?php echo $nfl ; ?></div>
								<?php
								}
							$nfl = 0 ;
							zuschlag_zubehoer( $merk_whngid ) ;
							if( $gebaude != '' AND $gebaude != '-' ) { $weo_txt = $gebaude . ', ' ; }
							$weo_txt = $weo_txt . $whngbez ;
//							if( $whnglage != '' ) { $weo_txt = $weo_txt . ', ' . $whnglage ; }
							if( $widmung != '' ) { $weo_txt = $weo_txt . ', ' . $widmung ; }
							if( $rwhng ) { $weo_txt = $weo_txt . ' (Regelwohnung)' ; }
							$merk_whngid = $idw ;

							?>
							<div class="clear"></div>
							<div class="trhd2"><?php echo $weo_txt ; ?></div>
							<?php

							$weo_txt = '' ;
							$merk_whngid = $idw ;
							unset( $table_room ) ;

							$num = 0 ;
							$abf = "SELECT RaumID FROM $db_raum WHERE InWohnungID = $idw ORDER BY Reihenfolge" ;
							$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
							while( $row1 = mysql_fetch_object( $erg ) ) {
								$num++ ;
								$raumnr = $row1->RaumID ;
								$abf2 = "UPDATE $db_raum SET Reihenfolge = '$num' WHERE RaumID=$raumnr" ;
								$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
								}
							$abf3 = "SELECT RaumID, Raumart, Lage, Anmerkung, Nebenraum, Flaeche FROM $db_raum WHERE InWohnungID = $merk_whngid ORDER BY Reihenfolge" ;
							$erg3 = mysql_query( $abf3 )  OR die( "Error: $abf3 <br>". mysql_error() ) ;
							while( $rw3 = mysql_fetch_object( $erg3 ) ) {
								$raumid = $rw3->RaumID ;
								$r_art	= $rw3->Raumart ;
								$r_lage	= $rw3->Lage ;
								$r_anm	= $rw3->Anmerkung ;
								$r_nr		= $rw3->Nebenraum ;
								$efl		= $rw3->Flaeche ;
								if( $r_anm == '' ) { $r_anm = '-' ; }
								$table_room [ ] = array( 'raumid'=>$raumid, 'r_art'=>$r_art, 'r_lage'=>$r_lage, 'r_anm'=>$r_anm, 'r_nr'=>$r_nr, 'efl'=>$efl ) ;
								}

							$k = 0 ;
							$anz_room = count( $table_room ) ;
							while( $k < $anz_room ) {
								$raumid = $table_room [ $k ][ 'raumid' ] ;
								$r_art	= $table_room [ $k ][ 'r_art' ] ;
								$r_lage	= $table_room [ $k ][ 'r_lage' ] ;
								$r_anm	= $table_room [ $k ][ 'r_anm' ] ;
								$r_nr		= $table_room [ $k ][ 'r_nr' ] ;
								$efl		= $table_room [ $k ][ 'efl' ] ;

								?>
								<div class="clear"></div>
								<div class="tr">
									<div class="sp1a"><?php echo $r_art ; ?></div>
									<div class="sp2a"><?php echo $r_anm ; ?></div>
									<div class="sp3a"><?php echo $r_lage ; ?></div>
									<div class="sp4"><input type=checkbox disabled <?php if( $r_nr == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>

									<?php
									$formel = '-' ;
									$rechng = '-' ;

									$erste = true ;

									$abf4 = "SELECT * FROM $db_abmessung WHERE vonRaumID = $raumid" ;
									$erg4 = mysql_query( $abf4 )  OR die( "Error: $abf4 <br>". mysql_error() ) ;
									while( $rw4 = mysql_fetch_object( $erg4 ) ) {
										$a		= $rw4->A ;
										$b		= $rw4->B ;
										$c		= $rw4->C ;
										$t		= $rw4->T ;
										$add	= $rw4->Addieren ;
										$form	= $rw4->Form ;
										$abf5 = "SELECT * FROM $db_form WHERE Form = '$form'" ;
										$erg5 = mysql_query( $abf5 )  OR die( "Error: $abf5 <br>". mysql_error() ) ;
										while( $rw5 = mysql_fetch_object( $erg5 ) ) {
											$formel		= $rw5->Formel ;
											$rechnung	= $rw5->zuRechnen ;

											$calc = '' ;
											$flaeche = 0 ;

											if( $formel != '' ) {
												$leng = strlen( $formel ) ;
												$n = 0 ;
												while( $n < $leng ) {
													$c = substr( $formel, $n, 1 ) ;
													switch( $c ) {
														case 'a' :
															$calc = $calc . $a ;
															break ;
														case 'b' :
															$calc = $calc . $b ;
															break ;
														case 'c' :
															$calc = $calc . $c ;
															break ;
														case 't' :
															$calc = $calc . $t ;
															break ;
														case '(' :
															$calc = $calc . '(' ;
															break ;
														case ')' :
															$calc = $calc . ')' ;
															break ;
														case '+' :
															$calc = $calc . '+' ;
															break ;
														case '-' :
															$calc = $calc . '-' ;
															break ;
														case '*' :
															$calc = $calc . '*' ;
															break ;
														case '/' :
															$calc = $calc . '/' ;
															break ;
														case '0' :
															$calc = $calc . '0' ;
															break ;
														case '1' :
															$calc = $calc . '1' ;
															break ;
														case '2' :
															$calc = $calc . '2' ;
															break ;
														case '3' :
															$calc = $calc . '3' ;
															break ;
														case '4' :
															$calc = $calc . '4' ;
															break ;
														case '5' :
															$calc = $calc . '5' ;
															break ;
														case '6' :
															$calc = $calc . '6' ;
															break ;
														case '7' :
															$calc = $calc . '7' ;
															break ;
														case '8' :
															$calc = $calc . '8' ;
															break ;
														case '9' :
															$calc = $calc . '9' ;
															break ;
														case 'W' :
															$calc = $calc . 'sqrt' ;
															$j = $j + 5 ;
															break ;
														case 'P' :
															$calc = $calc . '3.1416*' ;
															$j = $j + 2 ;
															break ;
														default:
															break ;
														}
													$n++ ;
													}
												eval( '$flaeche = (' . $calc . ');' );
												if( !$add ) { 
													$flaeche = -1 * abs( $flaeche ) ;
													$calc		= '-' . $calc ;
													$formel = '-' . $formel ;
													}
												$efl = $flaeche ;
												$gfl = $gfl + $efl ;
												$nfl = $nfl + $efl ;
												$efl = number_format( round( $efl, 2 ), 2, ',', '.' ) ;
												} 
	
											$rechng	= $calc ;

											if( $erste ) {
												$erste = false;
												?>
												<div class="sp5a"><?php echo $formel ; ?></div>
												<?php
												}
											else{
												?>
												<div class="sp5a"><?php echo $formel ; ?></div>
												<?php
												}
											?>
											<div class="sp5a"><?php echo $rechng ; ?></div>
											<div class="sp7"><?php echo $efl ; ?></div>
											<?php
											}
										}
									$gfl = $gfl + $efl ;
									$nfl = $nfl + $efl ;
									$efl = number_format( round( $efl, 2 ), 2, ',', '.' ) ;

									if( $formel == '-' ) {
										?>
										<div class="sp5a"><?php echo $formel ; ?></div>
										<div class="sp5a"><?php echo $rechng ; ?></div>
										<div class="sp7"><?php echo $efl ; ?></div>
										<?php
										}

									if( $raumid != $table_whng [ $i+1 ][ 'raumid' ] ) {
										$gfl = number_format( round( $gfl, 2 ), 2, ',', '.' ) ;
										?>
										<div class="sp7"><?php echo $gfl ; ?></div>
										<?php
										$gfl = 0 ;
										$merk_raumid = $raumid ;
										}
										?>
									<div class="col_buttonblk">
										<?php
										if( !$abgeschl ) {
											?>
											<button type="submit" name="detail" class="edit_buttn" value="<?php echo $raumid ; ?>"></button>
											<a href="copy_proc/cpy_raum.php?raum=<?php echo $raumid ; ?>" class="copy_buttn"></a>
											<a href="delete_includes/del_raum.php?raum=<?php echo $raumid ; ?>" class="delete_buttn"></a>
											<?php
											}
										?>
										<a href="gutachten_b4_2.php?wasid=<?php echo $raumid ; ?>" class="rechnen_buttn"></a>
										<?php
										if( !$abgeschl ) {
											if( $k > 0 ) {
												?>
												<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $raumid ; ?>"></button>
												<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $raumid ; ?>"></button>
												<?php
												}
											if( $k < $anz_room - 1 ) {
												if( $k == 0 ) {
													?>
													<button type="submit" name="runter"	class="runter_buttn_solo" value="<?php echo $raumid ; ?>"></button>
													<?php
													}
												else {
													?>
													<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $raumid ; ?>"></button>
													<?php
													}
												?>
												<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $raumid ; ?>"></button>
												<?php
												}
											}
											?>
										</div><!-- buttons -->
									</div>
								<?php
								$k++ ;
								} // while k
							} // if( $merk_whngid != $idw
						$i++ ;
						}  // while i

					zuschlag_zubehoer( $merk_whngid ) ;
					if( $nfl > 0 ) {
						$nfl = number_format( round( $nfl, 2 ), 2, ',', '.' ) ;
						?>
						<div class="sp9"><?php echo $nfl ; ?></div>
						<?php
						}
					$nfl = 0 ;
					?>
					</div> <!-- main_unten -->
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->