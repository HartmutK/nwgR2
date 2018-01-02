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

	$topic = 'Wohnungsbezeichnungen' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;


	if( isset( $_POST[ 'save' ] ) ) {
		$nummer = $_POST[ 'nummer' ] ;
		if( $nummer <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_wohnungnr SET Wohnungnr = '$nummer' WHERE ID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_wohnungnr (`ID`, `Wohnungnr`, `Aktiv` ) VALUES( NULL, '$nummer', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'Wohnungsbezeichnung gespeichert' ;
			$nummer = '' ;
			}
		else {
			$errortxt = 'Bezeichnung fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_wohnungnr WHERE ID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$nummer 	= $row->Wohnungnr ;
		$_SESSION[ 'merkid' ] = $row->ID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_wohnungnr SET Aktiv = 0 WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Wohnungsbezeichnung gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$nummer = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

// Liste aufbauen
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_wohnungnr WHERE Aktiv ORDER BY Wohnungnr" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$lageid	= $row->ID ;
		$nr		 	= $row->Wohnungnr ;
		$liste [ ] = array( 'lageid'=>$lageid, 'nr'=>$nr ) ;
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
						<div class="col_160"><input type="text" name="nummer" class="col_140" value="<?php echo $nummer ; ?>"></div>
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
					<div class="trhd">Bezeichnung</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$lageid	= $liste [ $i ][ 'lageid' ] ;
						$nr = $liste [ $i ][ 'nr' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_160"><?php echo $nr ; ?> </div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $lageid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $lageid ; ?>"></button>
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