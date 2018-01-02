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

	$topic = 'Gutachtentexte' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$txxt = $_REQUEST[ 'txxt' ] ;
		if( $txxt <> '' ) {
			$tableid = $_SESSION[ 'idie' ] ;
			unset( $_SESSION[ 'idie' ] ) ;
			$abfrage = "UPDATE $db_txt SET txt = '$txxt' WHERE txtID = $tableid" ;
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'Gutachtentext gespeichert' ;
			$txtart = '' ;
			$txxt		= '' ;
			$chnge	= false ;
			}
		else {
			$errortxt = 'Gutachtentext fehlt' ;
			}
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$_SESSION[ 'idie' ] = $tableid ;
		$abfrage = "SELECT * FROM $db_txt WHERE txtID = $tableid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$txtart = $row->TxtArt ;
			$txxt		= $row->txt ;
			$chnge	= true ;
			}
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_txt ORDER BY txtID" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id		= $row->txtID ;
		$art	= $row->TxtArt ;
		$tex	= $row->txt ;
		$liste [ ] = array( 'id'=>$id, 'art'=>$art, 'tex'=>$tex ) ;
		}

	if( $txtart == '' ) {
		$txtart = '.' ;
		}

	?>
<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben1">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_120"><?php echo $txtart ; ?></div>
						<div class="col_620"><textarea name="txxt" maxlength="64000" cols="122" rows="5" class="col_600" ><?php echo $txxt ; ?></textarea></div>
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
						<div class="col_120">Bezeichnung</div>
						<div class="col_420">Gutachtentext</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten1">
					<?php
					$i = 0 ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$id		= $liste [ $i ][ 'id' ] ;
						$art	= $liste [ $i ][ 'art' ] ;
						$tex	= $liste [ $i ][ 'tex' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_120"><?php echo $art ; ?> </div>
							<div class="col_620"><?php echo $tex ; ?> </div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $id ; ?>"></button>
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