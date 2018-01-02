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

	$topic = 'Einheiten' ;
	$wastxt = 'Einheit' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$einheit = $_POST[ 'einheit' ] ;
		if( $einheit <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_einheiten SET Einheit = '$einheit' WHERE ID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_einheiten (`ID`, `Einheit`, `Aktiv` ) VALUES( NULL, '$einheit', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = $wastxt . ' gespeichert' ;
			$einheit = '' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_einheiten WHERE ID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$einheit = $row->Einheit ;
		$_SESSION[ 'merkid' ] = $row->ID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_einheiten SET Aktiv = 0 WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . ' gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		$einheit = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	$list1 = array() ;
	$abfrage = "SELECT * FROM $db_einheiten WHERE Aktiv ORDER BY Einheit" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id	= $row->ID ;
		$unit = $row->Einheit ;
		$list1 [ ] = array( 'id'=>$id, 'unit'=>$unit ) ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_120"><input type="text" name="einheit" class="col_100" value="<?php echo $einheit ; ?>"></div>
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
							</div> <!-- buttons -->
							<div class="etxt"><?php echo $errortxt ; ?></div>
						</div> <!-- b5tabrow -->	
					<div class="clear"></div>
					<div class="trhd">Einheit</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz = count( $list1 ) ;
					while( $i < $anz ) {
						$id = $list1 [ $i ][ 'id' ] ;
						$unit = $list1 [ $i ][ 'unit' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_120"><?php echo $unit; ?></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $id ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $id ; ?>"></button>
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