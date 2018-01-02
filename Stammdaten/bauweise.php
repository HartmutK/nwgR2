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

	$topic = 'Bauweise' ;
	$wastxt = 'Bauweise ' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$bauweise = $_POST[ 'bauweise' ] ;
		if( $bauweise <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_bauweise SET Bauweise = '$bauweise' WHERE ID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				$errortxt = $wastxt . 'ge&auml;ndert' ;
				}
			else {
				$abfrage	= "INSERT INTO $db_bauweise ( `ID`, `Bauweise`, Aktiv ) VALUES( NULL, '$bauweise', 'true' )" ;
				$errortxt = $wastxt . 'gespeichert' ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$bauweise = '' ;
			}
		else {
			$errortxt = 'Bauweise fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_bauweise WHERE ID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$bauweise = $row->Bauweise ;
		$_SESSION[ 'merkid' ] = $row->ID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_bauweise SET Aktiv = 'false' WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . 'gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$bauweise = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_bauweise WHERE Aktiv ORDER BY Bauweise" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$ndxid	= $row->ID ;
		$ndx		= $row->Bauweise ;
		$liste [ ] = array( 'ndxid'=>$ndxid, 'ndx'=>$ndx ) ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_120"><input type="text" name="bauweise" class="col_100" value="<?php echo $bauweise ; ?>"></div>

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
					<div class="trhd">Bauweise</div>

					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz_liste = count( $liste ) ;
					$liste [ ] = array( 'ndxid'=>$ndxid, 'ndx'=>$ndx ) ;
					while( $i < $anz_liste ) {
						$ndxid	= $liste [ $i ][ 'ndxid' ] ;
						$ndx		= $liste [ $i ][ 'ndx' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_120"><?php echo $ndx ; ?> </div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $ndxid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $ndxid ; ?>"></button>
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