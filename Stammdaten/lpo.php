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

	$topic = 'PLZ / Ort' ;
	$wastxt = 'PLZ / Ort ' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

// Liste aufbauen
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_lpo ORDER BY PLZ" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id 		= $row->LPOID ;
		$land		= $row->Land ;
		$plz		= $row->PLZ ;
		$ort		= $row->Ort ;
		$bezirk	= $row->Bezirk ;
		$liste [ ] = array( 'id'=>$id, 'land'=>$land, 'plz'=>$plz, 'ort'=>$ort, 'bezirk'=>$bezirk ) ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$land		= $_POST[ 'land' ] ;
		$plz		= $_POST[ 'plz' ] ;
		$ort		= $_POST[ 'ort' ] ;
		$bezirk	= $_POST[ 'bezirk' ] ;
		if( $bezirk == '' ) {
			$bezirk = '-' ;
			}
		if( $plz <> '' AND $ort <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'merkid' ] ;
				$abfrage = "UPDATE $db_lpo SET Land = '$land', PLZ = '$plz', Ort = '$ort', Bezirk = '$bezirk' WHERE LPOID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_lpo ( `LPOID`, `Land`, `PLZ`, `Ort`, `Bezirk` ) VALUES( NULL, '$land', '$plz', '$ort', '$bezirk' )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = $wastxt . 'gespeichert' ;
			$id 		= NULL ;
			$land 	= '' ;
			$plz		= '' ;
			$ort		= '' ;
			$bezirk	= '' ;

			$liste = array( ) ;
			$abfrage = "SELECT * FROM $db_lpo ORDER BY PLZ" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			while( $row = mysql_fetch_object( $ergebnis ) ) {
				$id 		= $row->LPOID ;
				$land		= $row->Land ;
				$plz		= $row->PLZ ;
				$ort		= $row->Ort ;
				$bezirk	= $row->Bezirk ;
				$liste [ ] = array( 'id'=>$id, 'land'=>$land, 'plz'=>$plz, 'ort'=>$ort, 'bezirk'=>$bezirk ) ;
				}
			}
		else {
			$errortxt = 'PLZ oder Ort fehlt' ;
			}
		$chnge = false ;
		}

	if( isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_lpo WHERE LPOID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$id 		= $row->LPOID ;
		$land		= $row->Land ;
		$plz		= $row->PLZ ;
		$ort		= $row->Ort ;
		$bezirk	= $row->Bezirk ;
		$_SESSION[ 'merkid' ] = $id ;
		$chnge = true ;
		}
	else {
		$id 		= NULL ;
		$land 	= '' ;
		$plz		= '' ;
		$ort		= '' ;
		$bezirk	= '' ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_lpo WHERE LPOID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . 'gel&ouml;scht' ;
		$id 		= NULL ;
		$land 	= '' ;
		$plz		= '' ;
		$ort		= '' ;
		$bezirk	= '' ;
		unset( $_POST['del'] ) ;

		$liste = array( ) ;
		$abfrage = "SELECT * FROM $db_lpo ORDER BY PLZ" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$id 		= $row->LPOID ;
			$land		= $row->Land ;
			$plz		= $row->PLZ ;
			$ort		= $row->Ort ;
			$bezirk	= $row->Bezirk ;
			$liste [ ] = array( 'id'=>$id, 'land'=>$land, 'plz'=>$plz, 'ort'=>$ort, 'bezirk'=>$bezirk ) ;
			}
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
					<div class="col_60"><input type="text" name="plz" class="col_40" value="<?php echo $plz ; ?>"></div>
					<div class="col_120"><input type="text" name="ort" class="col_100" value="<?php echo $ort ; ?>"></div>
					<div class="col_140"><input type="text" name="bezirk" class="col_120" value="<?php echo $bezirk ; ?>"></div>

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
					<div class="errmsg"><?php echo $errortxt ; ?></div>
					</div>

					<div class="clear"></div>
					<div class="trhd">
						<div class="col_60">PLZ</div>
						<div class="col_120">Ort</div>
						<div class="col_140">Bezirk</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$kid	= $liste [ $i ][ 'id' ] ;
						$lnd	= $liste [ $i ][ 'land' ] ;
						$pl	= $liste [ $i ][ 'plz' ] ;
						$dorf	= $liste [ $i ][ 'ort' ] ;
						$bezrk	= $liste [ $i ][ 'bezirk' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_60"><?php echo $pl ; ?></div>
							<div class="col_120"><?php echo $dorf ; ?></div>
							<div class="col_140"><?php echo $bezrk ; ?> </div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $kid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $kid ; ?>"></button>
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