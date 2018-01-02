<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;

	if( isset( $_SESSION[ 'anmelden' ] ) ) {
		if( !$_SESSION[ 'anmelden' ][ 'angemeldet' ] ) { $ok = false ; }
		else { $ok = true ;	}
		}
	else {
		$ok = false ;
		}

if( $ok ) {
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'C.2' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}
	elseif( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		$abf1 = "SELECT AllgID, Reihenfolge FROM $db_allgflaeche WHERE InGutachtenID = $gutachtenid" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->AllgID;
			$num 		= 1 + $rw1->Reihenfolge ;
			$abf2 = "UPDATE $db_allgflaeche SET Reihenfolge = '$num' WHERE AllgID = $wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_allgflaeche SET Reihenfolge = '1' WHERE AllgID = $dokid " ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		unset( $_POST[ 'roof' ] ) ;
		}
	elseif( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT Reihenfolge FROM $db_allgflaeche WHERE AllgID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_allgflaeche SET Reihenfolge = $old_num WHERE InGutachtenID = $gutachtenid AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_allgflaeche SET Reihenfolge = $new_num WHERE AllgID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'rauf' ] ) ;
		}
	elseif( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT Reihenfolge FROM $db_allgflaeche WHERE AllgID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_allgflaeche SET Reihenfolge = $old_num WHERE InGutachtenID = $gutachtenid AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_allgflaeche SET Reihenfolge = $new_num WHERE AllgID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'runter' ] ) ;
		}
	elseif( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		$abf = "SELECT MAX( Reihenfolge ) AS Maxi FROM $db_allgflaeche WHERE InGutachtenID = $gutachtenid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$num	= $rw->Maxi + 1 ;
		$abf2 = "UPDATE $db_allgflaeche SET Reihenfolge = '$num' WHERE AllgID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		unset( $_POST[ 'floor' ] ) ;
		}
	elseif( isset( $_POST[ 'neu' ] ) ) {
		unset( $_POST[ 'neu' ] ) ;
		unset( $_SESSION[ 'merkid' ] ) ;
		$objkt	= $wohnungid ;
		$objlag	= '' ;
		$flart	= '' ;
		$flche	= 0 ;
		$savebuttn = true ;
		}

	elseif( isset( $_POST[ 'saveanmerk' ] ) ) {
		$anmerkungaf = $_POST[ 'anmerkungaf' ] ;
		$abfrage = "UPDATE $db_gutachten SET Anmerkung_AF = '$anmerkungaf' WHERE GutachtenID = $gutachtenid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		unset( $_POST[ 'saveanmerk' ] ) ;
		}

	elseif( isset( $_POST[ 'save' ] ) ) {
		$wohnungid = $_POST[ 'save' ] ;
		$objlag	= $_POST[ 'objlag' ] ;

		$abfrage = "SELECT SortPos FROM $db_lagen WHERE Aktiv AND Kuerzel = '$objlag' ;" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$sortiert	 = $row->SortPos ;
			}
		$flart	= $_POST[ 'flart' ] ;
		$flche	= $_POST[ 'flche' ] ;

		if( isset( $_SESSION[ 'merkid' ] ) ) {
			$wohnungid = $_SESSION[ 'merkid' ] ;
			$abfrage = "UPDATE $db_allgflaeche 
										SET Lage = '$objlag', Bereich = '$flart', Allgflaeche = '$flche', Sortiert = '$sortiert'
										WHERE AllgID = $wohnungid" ;
			$errortxt = 'Allgemeinfl&auml;che ge&auml;ndert' ;
			unset( $_SESSION[ 'merkid' ] ) ;
			$savebuttn = false ;
			}
		else {
			$objekt = $_POST[ 'objkt' ] ;
			$abfrage = "SELECT GebaeudeID FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid AND Bezeichnung = '$objekt'" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			while( $rw = mysql_fetch_object( $ergebnis ) ) {
				$wohnungid = $rw->GebaeudeID ;
				}
			$abfrage	= "INSERT INTO $db_allgflaeche (`AllgID`, `InGutachtenID`, `Lage`, `Bereich`, `Allgflaeche`, `Sortiert` )
										VALUES( NULL, '$gutachtenid', '$objlag', '$flart', '$flche', '$sortiert' )" ;
			$errortxt = 'Allgemeinfl&auml;che gespeichert' ;
			$savebuttn = true ;
			}
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;

		$flche = 0 ;
		unset( $_POST[ 'save' ] ) ;
		}

	elseif( isset( $_POST[ 'detail' ] ) ) {
		$ingid = $_POST[ 'detail' ] ;
		unset( $_SESSION[ 'merkid' ] ) ;
		$_SESSION[ 'merkid' ] = $ingid ;
		$abfrage = "SELECT * FROM $db_allgflaeche WHERE AllgID = $ingid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$allgid	= $row->AllgID ;
			$ingid	= $row->InGebaeudeID ;
			$objlag	= $row->Lage ;
			$flart	= $row->Bereich ;
			$flche	= $row->Allgflaeche ;
			}
		unset( $_POST[ 'detail' ] ) ;
		$savebuttn = true ;
		}

	elseif( isset( $_POST[ 'delete' ] ) ) {
		$allgid= $_POST[ 'delete' ] ;
		$errortxt = 'Allgemeinfl&auml;che gel&ouml;scht' ;
		$abfrage = "DELETE FROM $db_allgflaeche WHERE AllgID = $allgid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		unset( $_POST[ 'delete' ] ) ;
		$savebuttn = false ;
		}
	else {
		}

	$num = 0 ;
	$allgflaechen = array() ;
	$abfrage = "SELECT * FROM $db_allgflaeche WHERE InGutachtenID = $gutachtenid ORDER BY Reihenfolge" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$allgid = $row->AllgID ;
		$allglage = $row->Lage ;
		$allgbereich = $row->Bereich ;
		$allgflaeche = $row->Allgflaeche ;
		$num++ ;
		$allgflaechen [ ] = array( 'allgid'=>$allgid, 'allglage'=>$allglage, 'allgbereich'=>$allgbereich, 'allgflaeche'=>$allgflaeche ) ;
		$abfrag2 = "UPDATE $db_allgflaeche SET Reihenfolge = '$num' WHERE AllgID = $allgid" ;
		$ergebn2 = mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		}

	unset( $_SESSION[ 'objlage' ] ) ;
	$_SESSION[ 'objlage' ] = array() ;
	$objlage = array() ;
	$abfrage = "SELECT * FROM $db_lagen ORDER BY Reihenfolge" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$lagid = $row->LageID ;
		$lagen = $row->Kuerzel ;
		$objlage[ ] = array( 'lagid'=>$lagid, 'lagen'=>$lagen ) ;
		} // Lagen
	$_SESSION[ 'objlage' ] = $objlage ;

	unset( $_SESSION[ 'art' ] ) ;
	$_SESSION[ 'art' ] = array() ;
	$art = array() ;
	$abfrage = "SELECT * FROM $db_allgflarten ORDER BY Art" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$artid = $row->ID ;
		$arttxt = $row->Art ;
		$art[ ] = array( 'artid'=>$artid, 'arttxt'=>$arttxt ) ;
		} // allg. Flächenarten
	$_SESSION[ 'art' ] = $art ;


	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

//--------------------------------------------------------------------------------------

	include( "../php/head.php" ) ; 
	?>

		<div class="clear"></div>
		<div id="mainc2">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainc2_oben">

					<div class="clear"></div>
					<div class="trin">
						<?php
						if( !$abgeschl ) {
							?>
		 					<div class="col1">
			 					<?php
								$i = 0 ;
								$liste = $_SESSION[ 'objlage' ] ;
								$anz = count( $liste ) ;
								?>
								<select name="objlag" class="col1">
									<?php
									while( $i < $anz ) {
										$lagid	= $liste [ $i ][ 'lagid' ] ;
										$lagen 	= $liste [ $i ][ 'lagen' ] ;
										if( $lagen == $objlag ) { 
											echo '<option selected value="' . $lagen . '">' . $lagen . '</option>' ;
											}
										else { 	echo '<option value="' . $lagen . '">' . $lagen . '</option>'; }
										$i++ ;
										}
										?>
									</select>
								</div> <!-- auswliste -->
		 					<div class="col2">
			 					<?php
								$i = 0 ;
								$liste = $_SESSION[ 'art' ] ;
								$anz = count( $liste ) ;
								?>
								<select name="flart" class="col2">
									<?php
									while( $i < $anz ) {
										$artid	 = $liste [ $i ][ 'artid' ] ;
										$arttxt	 = $liste [ $i ][ 'arttxt' ] ;
										if( $arttxt == $flart ) { 	echo '<option selected value="' . $arttxt . '">' . $arttxt . '</option>'; }
										else { 	echo '<option value="' . $arttxt . '">' . $arttxt . '</option>'; }
										$i++ ;
										}
										?>
									</select>
								</div> <!-- auswliste -->
							<div class="col3"><input type="number" class="col3" name="flche" step="0.01" value="<?php echo $flche ; ?>"></div>
							<div class="col_buttons">
								<button type="submit" name="save" class="okay_buttn"></button>
								<?php
								if( $savebuttn ) {
									?>
									<button type="submit" name="bck" class="back_buttn"></button>
									<?php
									}
									?>
								<div class="col_buttons"><?php echo $errortxt ; ?></div>
								</div>
							<?php
							if( $savebuttn ) {
								?>
								<div class="col4">Anmerkung</div>
								<?php
								}
							else {
								?>
								<div class="col4a">Anmerkung</div>
								<?php
								}
								?>
							<div class="col5"><textarea name="anmerkungaf" maxlength="64000" cols="60" rows="5" class="col5" ><?php echo $anmerkungaf ; ?></textarea></div>
							<div class="col6">
								<button type="submit" name="saveanmerk" class="okay_buttn"></button>
								</div>

							<?php
							}
						?>
					</div> <!-- trin-->
	
					<div class="clear"></div>
					<div class="trhd">
						<div class="col1">Lage</div>
						<div class="col2">Allgemeinfl&auml;che</div>
						<div class="col3">Fl&auml;che in m&sup2;</div>
						</div>  <!-- tabrow -->	
					</div>  <!-- mainc2_oben -->	

				<div id="mainc2_unten">

					<?php
					$i = 0 ;
					$merkgeb = '' ;
					$anz = count( $allgflaechen ) ;
					while( $i < $anz ) {
						$allgid				= $allgflaechen [ $i ][ 'allgid' ] ;
						$allglage			= $allgflaechen [ $i ][ 'allglage' ] ;
						$allgbereich	= $allgflaechen [ $i ][ 'allgbereich' ] ;
						$allgflaeche	= $allgflaechen [ $i ][ 'allgflaeche' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col1"><?php echo $allglage ; ?></div>
							<div class="col2"><?php echo $allgbereich ; ?></div>
							<div class="col3"><?php echo number_format( round( $allgflaeche, 2 ), 2, ',', '.' ) ; ?></div>
							<?php
							if( !$abgeschl ) {
								?>
								<div class="col_buttons">
									<button type="submit" name="detail" class="edit_buttn" value="<?php echo $allgid  ; ?>"></button>
									<button type="submit" name="delete" class="delete_buttn" value="<?php echo $allgid ?> "></button>
									<?php
									if( $i > 0 ) {
										?>
										<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $allgid  ; ?>"></button>
										<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $allgid  ; ?>"></button>
										<?php
										}
									if( $i < $anz - 1 ) {
										if( $i == 0 ) {
											?>
											<button type="submit" name="runter"	class="runter_buttn_solo" value="<?php echo $allgid  ; ?>"></button>
											<?php
											}
										else {
											?>
											<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $allgid  ; ?>"></button>
											<?php
											}
										?>
										<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $allgid  ; ?>"></button>
										<?php
										}
									?>
									</div>
								</div>
								<?php
							}  // if !abgeschl
							?>
						<?php
						$i++ ;
						$allglage			= '' ;
						$allgbereich	= '' ;
						$allgflaeche	= 0 ;
						}  // while
					?>
					</div>  <!-- mainc2_unten -->	
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->