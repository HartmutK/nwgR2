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

	$topic = 'WE-Objektarten' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		unset( $_POST[ 'roof' ] ) ;
		$abf1 = "SELECT WEArtID, Reihenfolge FROM $db_weobjart WHERE Reihenfolge > 1" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$lageid = $rw1->WEArtID ;
			$num 		= 1 + $rw1->Reihenfolge ;
			$abf2 = "UPDATE $db_weobjart SET Reihenfolge = '$num' WHERE WEArtID = $lageid" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_weobjart SET Reihenfolge = '1' WHERE WEArtID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}

	if( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT Reihenfolge FROM $db_weobjart WHERE WEArtID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_weobjart SET Reihenfolge = $old_num WHERE Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_weobjart SET Reihenfolge = $new_num WHERE WEArtID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'rauf' ] ) ;
		}
	if( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT Reihenfolge FROM $db_weobjart WHERE WEArtID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_weobjart SET Reihenfolge = $old_num WHERE Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_weobjart SET Reihenfolge = $new_num WHERE WEArtID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'runter' ] ) ;
		}
	if( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		unset( $_POST[ 'floor' ] ) ;
		$abf = "SELECT Reihenfolge FROM $db_weobjart WHERE WEArtID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merkfolge	= $rw->Reihenfolge ;
		$abf1 = "SELECT WEArtID, Reihenfolge FROM $db_weobjart WHERE Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->WEArtID ;
			$num 		= $rw1->Reihenfolge - 1 ;
			$abf2 = "UPDATE $db_weobjart SET Reihenfolge = '$num' WHERE WEArtID = $wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$num 	= $num + 1 ;
		$abf2 = "UPDATE $db_weobjart SET Reihenfolge = '$num' WHERE LageID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$weart = $_POST[ 'weart' ] ;
		if( $weart <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_weobjart SET WEArt = '$weart' WHERE WEArtID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_weobjart (`WEArtID`, `WEArt`, `Aktiv` ) VALUES( NULL, '$weart', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'WE-Objektart gespeichert' ;
			$weart = '' ;
			}
		else {
			$errortxt = 'WE-Objektart fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_weobjart WHERE WEArtID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$weart = $row->WEArt ;
		$_SESSION[ 'merkid' ] = $row->WEArtID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_weobjart SET Aktiv = 0 WHERE WEArtID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'WE-Objektart gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		$weart = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	$num = 0 ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_weobjart WHERE Aktiv ORDER BY Reihenfolge" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id		= $row->WEArtID ;
		$ahrt		= $row->WEArt ;
		$num++ ;
		$abfrag2 = "UPDATE $db_weobjart SET Reihenfolge = '$num' WHERE WEArtID = $id" ;
		$ergebn2 = mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		$liste [ ] = array( 'id'=>$id, 'ahrt'=>$ahrt ) ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_270"><input type="text" name="weart" class="col_250" value="<?php echo $weart ; ?>"></div>
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
						<div class="etxt"><?php echo $errortxt ; ?></div>
						</div>
					<div class="clear"></div>
					<div class="trhd">WE-Objektart</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$id		= $liste [ $i ][ 'id' ] ;
						$ahrt	= $liste [ $i ][ 'ahrt' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_270"><?php echo $ahrt; ?> </div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $id ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $id ; ?>"></button>
								<?php
								if( $i > 0 ) {
									?>
									<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $id ; ?>"></button>
									<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $id ; ?>"></button>
									<?php
									}
								if( $i < $anz_liste - 1 ) {
									if( $i == 0 ) {
										?>
										<button type="submit" name="runter"	class="runter_buttn_solo" value="<?php echo $id ; ?>"></button>
										<?php
										}
									else {
										?>
										<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $id ; ?>"></button>
										<?php
										}
									?>
									<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $id ; ?>"></button>
									<?php
									}
									?>
								</div>
							</div>
						<?php
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