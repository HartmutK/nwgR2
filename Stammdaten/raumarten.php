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

	$topic = 'Raumarten' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$rmart  	= $_POST[ 'rmart' ] ;
		$wert			= $_POST[ 'wert' ] ;
		$einheit	= $_POST[ 'einheit' ] ;

		if( $rmart <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_raumart SET Raumart = '$rmart', Bewertung = '$wert', Einheit = '$einheit' WHERE RaumartID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				$errortxt = 'Raumart ge&auml;ndert' ;
				}
			else {
				$abfrage	= "INSERT INTO $db_raumart (`RaumartID`, `Raumart`, `Aktiv`, `Bewertung`, `Einheit` ) VALUES( NULL, '$rmart', true, '$wert', '$einheit' )" ;
				$errortxt = 'Raumart gespeichert' ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$rmart = '' ;
			$wert = 0 ;
			$einheit = '' ;
			}
		else {
			$errortxt = 'Bezeichnung fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_raumart WHERE RaumartID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$_SESSION[ 'merkid' ] = $row->RaumartID ;
		$rmart		= $row->Raumart ;
		$wert			= $row->Bewertung ;
		$einheit	= $row->Einheit ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_raumart SET Aktiv = 0 WHERE RaumartID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Raumart gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$rmart = '' ;
		$wert = 0 ;
		$einheit = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

// Formeln laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_raumart WHERE Aktiv ORDER BY Raumart" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$raumartid = $row->RaumartID ;
		$raumart	 = $row->Raumart ;
		$aktiv		 = $row->Aktiv ;
		$wrt			 = $row->Bewertung ;
		$ein			 = $row->Einheit ;
		if( $ein == '' ) {
			$ein = '-' ;
			}
		$liste [ ] = array( 'raumartid'=>$raumartid, 'raumart'=>$raumart, 'aktiv'=>$aktiv, 'wrt'=>$wrt, 'ein'=>$ein ) ;
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
						<div class="col_220"><input type="text" name="rmart" class="col_200" value="<?php echo $rmart ; ?>"></div>
						<div class="col_80"><input type="number" name="wert" class="col_60r" step="0.01" value="<?php echo $wert ; ?>"></div>
						<div>
							<?php
							echo '<select name="einheit" size="1" class="col_100">'; 
							$abfrage = "SELECT Einheit FROM $db_einheiten ORDER BY Einheit" ;
							$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
							while( $row = mysql_fetch_object( $erg ) ) {
								$wlage = $row->Einheit ;
								if( $wlage == $einheit ) { 	echo '<option selected value="' . $wlage . '">' . $wlage . '</option>'; }
								else { 	echo '<option value="' . $wlage . '">' . $wlage . '</option>'; }
								} //while - array füllen
							echo'</select>';
							?>
							</div>
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
					<div class="trhd">
						<div class="col_220">Bezeichnung</div>
						<div class="col_80">Bewertung</div>
						<div class="col_100">Einheit</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$raumartid	= $liste [ $i ][ 'raumartid' ] ;
						$raumart		= $liste [ $i ][ 'raumart' ] ;
						$wrt				= $liste [ $i ][ 'wrt' ] ;
						$ein				= $liste [ $i ][ 'ein' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_220"><?php echo $raumart ; ?></div>
							<div class="col_60r"><?php echo $wrt ; ?></div>
							<div class="col_100"><?php echo $ein ; ?></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $raumartid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $raumartid ; ?>"></button>
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