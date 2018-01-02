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

	$topic = 'Abk&uuml;rzungen' ;
	$wastxt = 'Abk&uuml;rzung' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$kurz = $_POST[ 'kurz' ] ;
		$lang = $_POST[ 'lang' ] ;
		if( $kurz <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_abkuerz SET Kuerzel = '$kurz', Langtext = '$lang' WHERE ID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_abkuerz (`ID`, `Kuerzel`, `Langtext`, `Aktiv` ) VALUES( NULL, '$kurz', '$lang', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = $wastxt . ' gespeichert' ;
			$kurz = '' ;
			$lang = '' ;
			$chnge = false ;
			}
		else {
			$errortxt = $wastxt . ' fehlt' ;
			}
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_abkuerz WHERE ID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$_SESSION[ 'merkid' ] = $row->ID ;
			$kurz = $row->Kuerzel ;
			$lang = $row->Langtext ;
			}
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = truae ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_abkuerz SET Aktiv = 0 WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . ' gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		$kurz = '' ;
		$lang = '' ;
		}

	$list1 = array() ;
	$abfrage = "SELECT * FROM $db_abkuerz WHERE Aktiv AND IngutachtenID = 0 ORDER BY Kuerzel" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id		= $row->ID ;
		$short	= $row->Kuerzel ;
		$long	= $row->Langtext ;
		$list1 [ ] = array( 'id'=>$id, 'short'=>$short, 'long'=>$long ) ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_80"><input type="text" name="kurz" class="col_60" value="<?php echo $kurz ; ?>"></div>
						<div class="col_220"><input type="text" name="lang" class="col_200" value="<?php echo $lang ; ?>"></div>
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
						</div> <!-- tr -->	
					<div class="clear"></div>
					<div class="trhd">
						<div class="col_80">Abk&uuml;rzung</div>
						<div class="col_200">Beschreibung</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz = count( $list1 ) ;
					while( $i < $anz ) {
						$id = $list1 [ $i ][ 'id' ] ;
						$short = $list1 [ $i ][ 'short' ] ;
						$long = $list1 [ $i ][ 'long' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_80"><?php echo $short; ?></div>
							<div class="col_220"><?php echo $long; ?></div>
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
			</div><!-- mains -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->