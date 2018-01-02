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

	$topic = 'Zubeh&ouml;r' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;


	if( isset( $_POST[ 'save' ] ) ) {
		unset( $_POST['save'] ) ;

		$zubehoer	= $_POST[ 'zubehoer' ] ;

		if( $zubehoer != '' ) {
			$min			= $_POST[ 'min' ] ;
			$zuschlag	= $_POST[ 'zuschlag' ] ;
			$max			= $_POST[ 'max' ] ;
			$einheit	= $_POST[ 'einheit' ] ;
			if( isset( $_POST[ 'unten' ] ) ) { $unten = true ; } else { $unten = false ; }
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_zubehoer SET Zubehoer = '$zubehoer', Min = '$min', ZuSchlag = '$zuschlag', Max = '$max', Einheit = '$einheit', GanzUnten = '$unten' WHERE ZubehoerID  = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_zubehoer (`ZubehoerID`, `Zubehoer`, `Min`, `ZuSchlag`, `Max`, `Einheit`, `GanzUnten` ) 
												VALUES( NULL, '$zubehoer', '$min', '$zuschlag', '$max', '$einheit', '$unten' )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			$zubehoer	= '' ;
			$min			= 0 ;
			$zuschlag	= 0 ;
			$max			= 0 ;
			$einheit	= '' ;
			$errortxt = 'Gespeichert' ;
			}
		else {
			$errortxt = 'Beschreibung' ;
			}
		$chnge = false ;
		}

	elseif( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_zubehoer WHERE ZubehoerID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt		= 'Gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		}

	elseif( isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_zubehoer WHERE ZubehoerID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$zubehoerID	= $row->ZubehoerID ;
		$zubehoer		= $row->Zubehoer ;
		$min				= $row->Min ;
		$zuschlag		= $row->ZuSchlag ;
		$max				= $row->Max ;
		$einheit		= $row->Einheit ;
		$_SESSION[ 'merkid' ] = $row->ZubehoerID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		unset( $_POST[ 'detail' ] ) ;
		$chnge = true ;
		}
	elseif( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}
	else {
		$zubehoer	= '' ;
		$min			= 0 ;
		$zuschlag	= 0 ;
		$max			= 0 ;
		$einheit	= '' ;
		}


// Tabelle laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_zubehoer ORDER BY Zubehoer" ;
	$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$zubehoerID	= $row->ZubehoerID ;
		$zub				= $row->Zubehoer ;
		$mn					= $row->Min ;
		$zuschl			= $row->ZuSchlag ;
		$mx					= $row->Max ;
		$einh				= $row->Einheit ;
		$down				= $row->GanzUnten ;
		$liste [ ] = array( 'zuabid'=>$zubehoerID, 'zub'=>$zub, 'mn'=>$mn, 'zuschl'=>$zuschl, 'mx'=>$mx, 'einh'=>$einh, 'down'=>$down ) ;
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
						<div class="col_620"><input type="text" name="zubehoer" class="col_600" value="<?php echo $zubehoer ; ?>"></div>
						<div class="col_80"><input type="number" name="min" class="col_60r" step="0.01" value="<?php echo $min ; ?>"></div>
						<div class="col_80"><input type="number" name="zuschlag" class="col_60r" step="0.01" value="<?php echo $zuschlag ; ?>"></div>
						<div class="col_80"><input type="number" name="max" class="col_60r" step="0.01" value="<?php echo $max ; ?>"></div>
						<div>
							<?php
							echo '<select name="einheit" size="1" class="col_120">'; 
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
						<div class="colyesno"><input type=checkbox <?php if( $unten == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="unten"/></div>
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
						</div>
					<div class="clear"></div>
					<div class="trhd">
						<div class="col_620">Zubeh&ouml;r</div>
						<div class="col_60r">Minimum</div>
						<div class="col_60r">NW / m&sup2;</div>
						<div class="col_60r">Maximum</div>
						<div class="col_120">Einheit</div>
						<div class="colyesno">Unten</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$zuabid	= $liste [ $i ][ 'zuabid' ] ;
						$zub		= $liste [ $i ][ 'zub' ] ;
						$mn			= $liste [ $i ][ 'mn' ] ;
						$zuschl	= $liste [ $i ][ 'zuschl' ] ;
						$mx			= $liste [ $i ][ 'mx' ] ;
						$einh		= $liste [ $i ][ 'einh' ] ;
						$down		= $liste [ $i ][ 'down' ] ;
						if( $einh == '' ) {
							$einh = '-' ;
							}
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_620"><?php echo $zub ; ?></div>
							<div class="col_60r"><?php echo $mn ; ?></div>
							<div class="col_60r"><?php echo $zuschl ; ?></div>
							<div class="col_60r"><?php echo $mx ; ?></div>
							<div class="col_120"><?php echo $einh ; ?></div>
							<div class="colyesno"><input disabled type=checkbox <?php if( $down == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?>/></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $zuabid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $zuabid ; ?>"></button>
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