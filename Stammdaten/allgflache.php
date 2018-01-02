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

	$topic = 'Allgemeinfl&auml;chen' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$rmart  = $_POST[ 'rmart' ] ;

		if( $rmart <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_allgflarten SET Art = '$rmart' WHERE ID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				$errortxt = 'Raumart ge&auml;ndert' ;
				}
			else {
				$abfrage	= "INSERT INTO $db_allgflarten (`ID`, `Art` ) VALUES( NULL, '$rmart' )" ;
				$errortxt = 'Raumart gespeichert' ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$rmart = '' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_allgflarten WHERE ID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$rmart = $row->Art ;
		$_SESSION[ 'merkid' ] = $row->ID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_allgflarten WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Raumart gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$rmart = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

// Formeln laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_allgflarten ORDER BY Art" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$raumartid = $row->ID ;
		$raumart	 = $row->Art ;
		$liste [ ] = array( 'raumartid'=>$raumartid, 'raumart'=>$raumart ) ;
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
						<div class="col_420"><input type="text" name="rmart" class="col_400" value="<?php echo $rmart ; ?>"></div>
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
					<div class="trhd">Allgemeinfl&auml;chenarten</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$raumartid	= $liste [ $i ][ 'raumartid' ] ;
						$raumart		= $liste [ $i ][ 'raumart' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_420"><?php echo $raumart ; ?></div>
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
			</div><!-- mains -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->