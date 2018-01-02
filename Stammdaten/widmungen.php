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

	$topic = 'Widmungen' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		unset( $_POST[ 'roof' ] ) ;
		$abf1 = "SELECT WidmungID, Reihenfolge FROM $db_widmung WHERE Reihenfolge > 1" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$lageid = $rw1->WidmungID;
			$num 		= 1 + $rw1->Reihenfolge ;
			$abf2 = "UPDATE $db_widmung SET Reihenfolge = '$num' WHERE WidmungID = $lageid" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_widmung SET Reihenfolge = '1' WHERE WidmungID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}

	if( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT Reihenfolge FROM $db_widmung WHERE WidmungID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_widmung SET Reihenfolge = $old_num WHERE Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_widmung SET Reihenfolge = $new_num WHERE WidmungID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'rauf' ] ) ;
		}
	if( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT Reihenfolge FROM $db_widmung WHERE WidmungID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_widmung SET Reihenfolge = $old_num WHERE Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_widmung SET Reihenfolge = $new_num WHERE WidmungID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'runter' ] ) ;
		}
	if( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		unset( $_POST[ 'floor' ] ) ;
		$abf = "SELECT Reihenfolge FROM $db_widmung WHERE WidmungID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merkfolge	= $rw->Reihenfolge ;
		$abf1 = "SELECT WidmungID, Reihenfolge FROM $db_widmung WHERE Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->WidmungID ;
			$num 		= $rw1->Reihenfolge - 1 ;
			$abf2 = "UPDATE $db_widmung SET Reihenfolge = '$num' WHERE WidmungID = $wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$num 	= $num + 1 ;
		$abf2 = "UPDATE $db_widmung SET Reihenfolge = '$num' WHERE WidmungID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$widmung = $_POST[ 'widmung' ] ;
		if( $widmung <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_widmung SET Widmung = '$widmung', RNW = '$rnw' WHERE WidmungID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_widmung (`WidmungID`, `Widmung`, `RNW`, `Aktiv` ) VALUES( NULL, '$widmung', '$rnw', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'Widmung gespeichert' ;
			$widmung = '' ;
			}
		else {
			$errortxt = 'Bezeichnung fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_widmung WHERE WidmungID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$widmung = $row->Widmung ;
		$rnw = $row->RNW ;
		$_SESSION[ 'merkid' ] = $row->WidmungID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_widmung SET Aktiv = 0 WHERE WidmungID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Widmung gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$widmung = '' ;
		$RNW = 0 ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$num = 0 ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_widmung WHERE Aktiv ORDER BY Reihenfolge" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$widmgid	= $row->WidmungID ;
		$widmng 	= $row->Widmung ;
		$widmrnw	= $row->RNW ;
		$aktiv		= $row->Aktiv ;
		$num++ ;
		$abfrag2 = "UPDATE $db_widmung SET Reihenfolge = '$num' WHERE WidmungID = $widmgid" ;
		$ergebn2 = mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		$liste [ ] = array( 'widmngid'=>$widmgid, 'widmng'=>$widmng, 'widmrnw'=>$widmrnw, 'aktiv'=>$aktiv ) ;
		}
	$_SESSION[ 'liste' ] = $liste ;
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_420"><input type="text" name="widmung" maxlength="50" class="col_400" value="<?php echo $widmung ; ?>"></div>
						<div class="col_80r"><input type="number" class="col_80r" maxlength="10" name="rnw" step="0.0001" value="<?php echo $rnw ?>"></div>
						<div class="col_buttons">
							<button type="submit" name="save" class="okay_buttn"></button>
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
					<div class="trhd">
						<div class="col_420">Bezeichnung</div>
						<div class="col_80r">RNW</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$widmngid	= $liste [ $i ][ 'widmngid' ] ;
						$widmng		= $liste [ $i ][ 'widmng' ] ;
						$widmrnw	= $liste [ $i ][ 'widmrnw' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_420"><?php echo $widmng ; ?></div>
							<div class="col_80r"><?php echo $widmrnw ; ?></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $widmngid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $widmngid ; ?>"></button>
								<?php
								if( $i > 0 ) {
									?>
									<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $widmngid ; ?>"></button>
									<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $widmngid ; ?>"></button>
									<?php
									}
								if( $i < $anz_liste - 1 ) {
									if( $i == 0 ) {
										?>
										<button type="submit" name="runter"	class="runter_buttn_solo" value="<?php echo $widmngid ; ?>"></button>
										<?php
										}
									else {
										?>
										<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $widmngid ; ?>"></button>
										<?php
										}
									?>
									<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $widmngid ; ?>"></button>
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