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

	$topic = 'Kastralgemeinden' ;
	$wastxt = 'Kastralgemeinde ' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

// Liste aufbauen
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_kastral ORDER BY Nummer" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id 			= $row->KastralID ;
		$nr 			= $row->Nummer ;
		$bezirk		= $row->Bezirk ;
		$gemeinde = $row->Gemeinde ;
		$gericht	= $row->Gericht ;
		$liste [ ] = array( 'id'=>$id, 'nr'=>$nr, 'bezirk'=>$bezirk, 'gemeinde'=>$gemeinde, 'gericht'=>$gericht ) ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$nr 			= $_POST[ 'nr' ] ;
		$bezirk		= $_POST[ 'bezirk' ] ;
		$gemeinde = $_POST[ 'gemeinde' ] ;
		$gericht	= $_POST[ 'gericht' ] ;
		if( $gericht == '' ) {
			$gericht = '-' ;
			}
		if( $nr <> '' AND $bezirk <> '' AND $gemeinde <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'merkid' ] ;
				$abfrage = "UPDATE $db_kastral SET Nummer = '$nr', Bezirk = '$bezirk', Gemeinde = '$gemeinde', Gericht = '$gericht' WHERE KastralID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_kastral (`KastralID`, `Nummer`, `Bezirk`, `Gemeinde`, `Gericht` ) VALUES( NULL, '$nr', '$bezirk', '$gemeinde', '$gericht' )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = $wastxt . 'gespeichert' ;
			$id 			= NULL ;
			$nr 			= '' ;
			$bezirk		= '' ;
			$gemeinde = '' ;
			$gericht	= '' ;

			$liste = array( ) ;
			$abfrage = "SELECT * FROM $db_kastral ORDER BY Nummer" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			while( $row = mysql_fetch_object( $ergebnis ) ) {
				$id 			= $row->KastralID ;
				$nr 			= $row->Nummer ;
				$bezirk		= $row->Bezirk ;
				$gemeinde = $row->Gemeinde ;
				$gericht	= $row->Gericht ;
				$liste [ ] = array( 'id'=>$id, 'nr'=>$nr, 'bezirk'=>$bezirk, 'gemeinde'=>$gemeinde, 'gericht'=>$gericht ) ;
				}
			}
		else {
			$errortxt = 'Nr., Bezirk oder Gemeinde fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_kastral WHERE KastralID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$id 			= $row->KastralID ;
		$nr 			= $row->Nummer ;
		$bezirk		= $row->Bezirk ;
		$gemeinde = $row->Gemeinde ;
		$gericht	= $row->Gericht ;
		$_SESSION[ 'merkid' ] = $id ;
		$chnge = true ;
		}
	else {
		$id 			= NULL ;
		$nr 			= '' ;
		$bezirk		= '' ;
		$gemeinde = '' ;
		$abk			= '' ;
		$gericht	= '' ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_kastral WHERE KastralID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . 'gel&ouml;scht' ;
		$id 			= NULL ;
		$nr 			= '' ;
		$bezirk		= '' ;
		$gemeinde = '' ;
		$gericht	= '' ;
		unset( $_POST['del'] ) ;

		$liste = array( ) ;
		$abfrage = "SELECT * FROM $db_kastral ORDER BY Nummer" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$id 			= $row->KastralID ;
			$nr 			= $row->Nummer ;
			$bezirk		= $row->Bezirk ;
			$gemeinde = $row->Gemeinde ;
			$gericht	= $row->Gericht ;
			$liste [ ] = array( 'id'=>$id, 'nr'=>$nr, 'bezirk'=>$bezirk, 'gemeinde'=>$gemeinde, 'gericht'=>$gericht ) ;
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
						<div class="col_80"><input type="text" name="nr" class="col_60" value="<?php echo $nr ?>"></div>
						<div class="col_160"><input type="text" name="bezirk" class="col_140" value="<?php echo $bezirk ?>"></div>
						<div class="col_270"><input type="text" name="gemeinde" class="col_250" value="<?php echo $gemeinde ?>"></div>
						<div class="col_140"><input type="text" name="gericht" class="col_120" value="<?php echo $gericht ?>"></div>
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
						<div class="col_80">Nr.</div>
						<div class="col_160">Bezirk</div>
						<div class="col_270">Kastralgemeinde</div>
						<div class="col_140">Bezirksgericht</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$keyid	= $liste [ $i ][ 'id' ] ;
						$nummer	= $liste [ $i ][ 'nr' ] ;
						$bezrk	= $liste [ $i ][ 'bezirk' ] ;
						$gemnde	= $liste [ $i ][ 'gemeinde' ] ;
						$geriht = $liste [ $i ][ 'gericht' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_80"><?php echo $nummer ; ?> </div>
							<div class="col_160"><?php echo $bezrk ; ?> </div>
							<div class="col_270"><?php echo $gemnde ; ?></div>
							<div class="col_140"><?php echo $geriht ; ?></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $keyid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $keyid ; ?>"></button>
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