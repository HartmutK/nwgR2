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

	$topic = 'Rechtspflegertexte' ;
	$wastxt = 'Rechtspflegertext' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$rtxt = $_POST[ 'rtxt' ] ;
		if( $rtxt <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_rpfleger SET Rechtspflegertext = '$rtxt' WHERE ID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_rpfleger (`ID`, `Rechtspflegertext`, `Aktiv` ) VALUES( NULL, '$rtxt', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = $wastxt . ' gespeichert' ;
			$rtxt = '' ;
			}
		else {
			$errortxt = $wastxt . ' fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_rpfleger WHERE ID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$rtxt = $row->Rechtspflegertext ;
		$_SESSION[ 'merkid' ] = $row->ID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_rpfleger SET Aktiv = 0 WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . ' gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		$rtxt = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_rpfleger WHERE Aktiv ORDER BY Rechtspflegertext" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id		= $row->ID ;
		$txt	= $row->Rechtspflegertext ;
		$liste [ ] = array( 'id'=>$id, 'txt'=>$txt ) ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_220"><input type="text" name="rtxt" class="col_200" value="<?php echo $rtxt ; ?>"></div>
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
					<div class="trhd">Rechtspflegertext</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$id		= $liste [ $i ][ 'id' ] ;
						$txt	= $liste [ $i ][ 'txt' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_220"><?php echo $txt; ?> </div>
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