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

	$topic = 'Wohnungslagen' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		unset( $_POST[ 'roof' ] ) ;
		$abf1 = "SELECT LageID, Reihenfolge FROM $db_lagen WHERE Reihenfolge > 1" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$lageid = $rw1->LageID ;
			$num 		= 1 + $rw1->Reihenfolge ;
			$abf2 = "UPDATE $db_lagen SET Reihenfolge = '$num' WHERE LageID = $lageid" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_lagen SET Reihenfolge = '1' WHERE LageID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}

	elseif( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT Reihenfolge FROM $db_lagen WHERE LageID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_lagen SET Reihenfolge = $old_num WHERE Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_lagen SET Reihenfolge = $new_num WHERE LageID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'rauf' ] ) ;
		}
	elseif( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT Reihenfolge FROM $db_lagen WHERE LageID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_lagen SET Reihenfolge = $old_num WHERE Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_lagen SET Reihenfolge = $new_num WHERE LageID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST[ 'runter' ] ) ;
		}
	elseif( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		unset( $_POST[ 'floor' ] ) ;
		$abf = "SELECT Reihenfolge FROM $db_lagen WHERE LageID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merkfolge	= $rw->Reihenfolge ;
		$abf1 = "SELECT LageID, Reihenfolge FROM $db_lagen WHERE Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$wohnnr = $rw1->LageID ;
			$num 		= $rw1->Reihenfolge - 1 ;
			$abf2 = "UPDATE $db_lagen SET Reihenfolge = '$num' WHERE LageID = $wohnnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$num 	= $num + 1 ;
		$abf2 = "UPDATE $db_lagen SET Reihenfolge = '$num' WHERE LageID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'save' ] ) ) {
		$sortiert = $_POST[ 'sortiert' ] ;
		$kuerzel = $_POST[ 'kuerzel' ] ;
		$lage = $_POST[ 'lage' ] ;
		if( $kuerzel <> '' AND $lage <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_lagen SET Kuerzel = '$kuerzel', Lage = '$lage', SortPos = '$sortiert' WHERE LageID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_lagen (`LageID`, `Kuerzel`, `Lage`, `SortPos`, `Aktiv` ) VALUES( NULL, '$kuerzel', '$lage', '$sortiert', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'Wohnungslage gespeichert' ;
			$kuerzel = '' ;
			$lage = '' ;
			$sortiert = 0 ;
			}
		else {
			$errortxt = 'K&uuml;rzel oder Beschreibung fehlt' ;
			}
		$chnge = false ;
		}
	elseif(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_lagen WHERE LageID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$_SESSION[ 'merkid' ] = $row->LageID ;
			$kuerzel	= $row->Kuerzel ;
			$lage			= $row->Lage ;
			$sortiert	= $row->SortPos ;
			}
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}
	elseif( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_lagen SET Aktiv = 0 WHERE LageID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Wohnungslage gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$kuerzel = '' ;
		$lage = '' ;
		$sortiert = 0 ;
		}
	elseif( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}
	else {
		}


// Formeln laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;

	$num = 0 ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_lagen WHERE Aktiv ORDER BY Reihenfolge" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$lageid	= $row->LageID ;
		$kuerz	= $row->Kuerzel ;
		$lag		= $row->Lage ;
		$num++ ;
		$abfrag2 = "UPDATE $db_lagen SET Reihenfolge = '$num' WHERE LageID = $lageid" ;
		$ergebn2 = mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		$liste [ ] = array( 'lageid'=>$lageid, 'kuerz'=>$kuerz, 'lag'=>$lag ) ;
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
						<div class="col_100"><input type="text" name="kuerzel" maxlength="15" class="col_80" value="<?php echo $kuerzel ; ?>"></div>
						<div class="col_420"><input type="text" name="lage" maxlength="50" class="col_400" value="<?php echo $lage ; ?>"></div>
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
						<div class="col_100">K&uuml;rzel</div>
						<div class="col_420">Beschreibung</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
				<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz = count( $liste ) ;
					while( $i < $anz ) {
						$lageid	= $liste [ $i ][ 'lageid' ] ;
						$kuerz	= $liste [ $i ][ 'kuerz' ] ;
						$lag		= $liste [ $i ][ 'lag' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_100"><?php echo $kuerz ; ?></div>
							<div class="col_420"><?php echo $lag ; ?></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $lageid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $lageid ; ?>"></button>
								<?php
								if( $i > 0 ) {
									?>
									<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $lageid  ; ?>"></button>
									<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $lageid  ; ?>"></button>
									<?php
									}
								if( $i < $anz - 1 ) {
									if( $i == 0 ) {
										?>
										<button type="submit" name="runter"	class="runter_buttn_solo" value="<?php echo $lageid  ; ?>"></button>
										<?php
										}
									else {
										?>
										<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $lageid  ; ?>"></button>
										<?php
										}
									?>
									<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $lageid  ; ?>"></button>
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