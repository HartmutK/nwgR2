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
	$_SESSION[ 'navi' ] = 'Stammdat' ;

	$topic = 'Zu- / Abschl&auml;ge' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;


	if( isset( $_POST[ 'save' ] ) ) {
		unset( $_POST['save'] ) ;
		$min				= $_POST[ 'min' ] ;
		$wieviel		= $_POST[ 'wieviel' ] ;
		$max				= $_POST[ 'max' ] ;
		if( $min <> 0 AND $max <> 0 AND ( abs( $wieviel ) < abs( $min ) OR abs( $wieviel ) > abs( $max ) ) ) {
			$errortxt = 'Menge muss zwischen Min und Max liegen' ;
			}
		else {
			$gruppe			= $_POST[ 'rmart' ] ;
			$kuerzel		= $_POST[ 'kuerzel' ] ;
			$einheit		= $_POST[ 'einheit' ] ;
			$kommentar	= $_POST[ 'kommentar' ] ;
			$gueltigab	= 0 ;
			if( isset( $_POST[ 'fest' ] ) ) { $fest = true ; } else { $fest = false ; }

			if( $kommentar <> '' ) {
				if( isset( $_SESSION[ 'merkid' ] ) ) {
					$tableid = $_SESSION[ 'tableid' ] ;
					$abfrage = "UPDATE $db_zuabschlag 
											SET Gruppe = '$gruppe', Kuerzel = '$kuerzel', Min = '$min', Wieviel = '$wieviel',  Max = '$max', Einheit = '$einheit', Kommentar = '$kommentar', 
											fix = '$fest', Gueltig_Ab = '$gueltigab' 
											WHERE ZuAbID= '$tableid'" ;
					unset( $_SESSION['merkid'] ) ;
					}
				else {
					$abfrage	= "INSERT INTO $db_zuabschlag (`ZuAbID`, `Gruppe`, `Kuerzel`, `Min`, `Wieviel`, `Max`, `Einheit`, `Kommentar`, `fix`, `Gueltig_Ab`, `Aktiv` ) 
												VALUES( NULL, '$gruppe', '$kuerzel', '$min', '$wieviel', '$max', '$einheit', '$kommentar', '$fest', '$gueltigab', true )" ;
					}
				$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				$errortxt 	= 'Zu-/Abschlag gespeichert' ;
				$gruppe			= '' ;
				$kuerzel		= '' ;
				$min				= 0 ;
				$wieviel		= 0 ;
				$max				= 0 ;
				$einheit 		= '' ;
				$kommentar	= '' ;
				$gueltigab	= '' ;
				$fest				= true ;
				}
			else {
				$errortxt = 'Beschreibung fehlt' ;
				}
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_zuabschlag WHERE ZuAbID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$gruppe			= $row->Gruppe ;
		$kuerzel		= $row->Kuerzel ;
		$min				= $row->Min ;
		$wieviel		= $row->Wieviel ;
		$max				= $row->Max ;
		$einheit 		= $row->Einheit ;
		$kommentar	= $row->Kommentar ;
		$gueltigab	= $row->Gueltig_Ab ;
		$fest				= $row->fix;
		$_SESSION[ 'merkid' ] = $row->ZuAbID;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_zuabschlag SET Aktiv = 0 WHERE ZuAbID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST['delete'] ) ;
		$errortxt		= 'Zu-/Abschlag gel&ouml;scht' ;
		$gruppe			= '' ;
		$kuerzel		= '' ;
		$min				= 0 ;
		$wieviel		= 0 ;
		$max				= 0 ;
		$einheit 		= '' ;
		$kommentar	= '' ;
		$gueltigab	= '' ;
		$fx					= true ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

// Zu-/Abschläge laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_zuabschlag WHERE Aktiv ORDER BY Gruppe DESC, Kuerzel" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$zuabid		= $row->ZuAbID ;
		$grp			= $row->Gruppe ;
		$kuerz		= $row->Kuerzel ;
		$mn				= $row->Min ;
		$viel			= $row->Wieviel ;
		$mx				= $row->Max ;
		$einh		 	= $row->Einheit ;
		$fx				= $row->fix;
		$komment	= $row->Kommentar ;
		$gueltig	= $row->Gueltig_Ab ;
		$liste [ ] = array( 'zuabid'=>$zuabid, 'kuerz'=>$kuerz, 'grp'=>$grp, 'mn'=>$mn, 'viel'=>$viel, 'mx'=>$mx, 'einh'=>$einh, 'fx'=>$fx, 'komment'=>$komment, 'gueltig'=>$gueltig ) ;
		}
	$_SESSION[ 'liste' ] = $liste ;

// Zu-/Abschl. Gruppen laden
	unset( $_SESSION[ 'grppen' ] ) ;
	$_SESSION[ 'grppen' ] = array() ;
	$grppen= array( ) ;
	$abfrage = "SELECT * FROM $db_zuablagen ORDER BY ZuAbLagen" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$zuabid		= $row->ZuAbLagenID ;
		$zuablage	= $row->ZuAbLagen ;
		$grppen [ ] = array( 'zuabid'=>$zuabid, 'zuablage'=>$zuablage ) ;
		}
	$_SESSION[ 'grppen' ] = $grppen ;
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_140">
							<?php
							echo '<select name="rmart" size="1" class="col_120">'; 
							$abfrage = "SELECT ZuAbLagen FROM $db_zuablagen ORDER BY ZuAbLagen" ;
							$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
							while( $row = mysql_fetch_object( $ergeb ) ) {
								$rmart = $row->ZuAbLagen ;
								if( $rmart == $zuab_gruppe ) { 	echo '<option selected value="' . $rmart . '">' . $rmart . '</option>'; }
								else { 	echo '<option value="' . $rmart . '">' . $rmart . '</option>'; }
								} //while - array füllen
							echo'</select>';
							?>
							</div>
						<div class="col_420"><input type="text" name="kommentar" class="col_400" value="<?php echo $kommentar ; ?>"></div>
						<div class="col_60"><input type="text" name="kuerzel" class="col_40" value="<?php echo $kuerzel ; ?>"></div>
						<div class="col_100"><input type="number" name="min" class="col_80r" step="0.01" value="<?php echo $min ; ?>"></div>
						<div class="col_100"><input type="number" name="wieviel" class="col_80r" step="0.01" value="<?php echo $wieviel ; ?>"></div>
						<div class="col_100"><input type="number" name="max" class="col_80r" step="0.01" value="<?php echo $max ; ?>"></div>
						<div class="col_40c"><input type="checkbox" class="col_40c" <?php if( $fest == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="fest" /></div>
	
					<div class="col_buttons">
						<button type="submit" Name="save" class="okay_buttn"></button>
						<?php
						if( $chnge ) {
							?>
							<button type="submit" Name="back" class="back_buttn"></button>
							<?php
							}
						else {
							?>
							<a href="../index.php?seite=admin" class="back_buttn"></a>
							<?php
							}
							?>
						</div>
						<a href="print/prt_ZuAbschlag.php" target="_blank" class="print_buttn"></a>
						<div class="etxt"><?php echo $errortxt ; ?></div>
						</div>
					<div class="clear"></div>
					<div class="trhd">
						<div class="col_140">Gruppe</div>
						<div class="col_420">Beschreibung</div>
						<div class="col_60">K&uuml;rzel</div>
						<div class="col_80r">Minimum</div>
						<div class="col_80r">Wert</div>
						<div class="col_80r">Maximun</div>
						<div class="col_40c">Fest</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$merkgrp = '' ;
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$zuabid		= $liste [ $i ][ 'zuabid' ] ;
						$grp			= $liste [ $i ][ 'grp' ] ;
						$kuerz		= $liste [ $i ][ 'kuerz' ] ;
						$mn				= $liste [ $i ][ 'mn' ] ;
						$viel			= $liste [ $i ][ 'viel' ] ;
						$mx				= $liste [ $i ][ 'mx' ] ;
						$einh			= $liste [ $i ][ 'einh' ] ;
						$fx				= $liste [ $i ][ 'fx' ] ;
						$komment	= $liste [ $i ][ 'komment' ] ;
						$gueltig	= $liste [ $i ][ 'gueltig' ] ;
						if( $grp == '' ) {
							$grp = 'Keine Zuordnung' ;
							}
						if( $merkgrp != $grp ) {
							$gp = $grp ;
							}
						else {
							$gp = '-' ;
							}
						if( $kuerz == '' ) {
							$kuerz = '-' ;
							}
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_140"><?php echo $gp ; ?></div>
							<div class="col_420"><?php echo $komment ; ?></div>
							<div class="col_60"><?php echo $kuerz ; ?></div>
							<div class="col_80r"><?php echo $mn ; ?></div>
							<div class="col_80r"><?php echo $viel ; ?></div>
							<div class="col_80r"><?php echo $mx ; ?></div>
							<div class="col_40c"><input type="checkbox" disabled <?php if( $fx == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $zuabid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $zuabid ; ?>"></button>
								</div>
							</div>
						<?php
						$merkgrp = $grp ;
						$i++ ;
						}
						?>
					</div> <!-- mains_unten -->
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->