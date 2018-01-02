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
	$_SESSION[ 'navi' ] = 'C.12' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = '$gutachtenid'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	$topic = 'Eintrag' ;
	$wastxt = 'Eintrag ' ;
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
				$abfrage	= "INSERT INTO $db_abkuerz (`ID`, `InGutachtenID`, `Kuerzel`, `Langtext`, `Aktiv` ) VALUES( NULL, '$gutachtenid', '$kurz', '$lang', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			$errortxt = $wastxt . ' gespeichert' ;
			$kurz = '' ;
			$lang = '' ;
			}
		else {
			$errortxt = $wastxt . ' fehlt' ;
			}
		unset( $_POST['save'] ) ;
		$chnge = false ;
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
		unset( $_POST['detail'] ) ;
		$chnge = true ;
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
	$abfrage = "SELECT * FROM $db_abkuerz WHERE Aktiv AND InGutachtenID = $gutachtenid ORDER BY Kuerzel" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$id		= $row->ID ;
		$short	= $row->Kuerzel ;
		$long	= $row->Langtext ;
		$list1 [ ] = array( 'id'=>$id, 'short'=>$short, 'long'=>$long ) ;
		}

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;
	?>
<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mainc12">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainc12_oben">
					<?php
					if( !$abgeschl ) {
						?>
						<div class="clear"></div>
						<div class="trin">
							<div class="col1"><input type="text" name="kurz" class="col1a" value="<?php echo $kurz ; ?>"></div>
							<div class="col2"><input type="text" name="lang" class="col2a" value="<?php echo $lang ; ?>"></div>
							<div class="col_buttons">
								<button type="submit" Name="save" class="okay_buttn"></button>
								<?php
								if( $chnge ) {
									?>
									<button type="submit" Name="back" class="back_buttn"></button>
									<?php
									}
									?>
								</div> <!-- buttons -->
								<div class="etxt"><?php echo $errortxt ; ?></div>
							</div> <!-- tr -->	
							<?php
							}
							?>
					<div class="clear"></div>
					<div class="trhd">
						<div class="col1">Weitere Abk.</div>
						<div class="col2">Beschreibung</div>
						</div>
					</div> <!-- main12_oben -->
				<div id="mainc12_unten">
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
							<div class="col1"><?php echo $short; ?></div>
							<div class="col2"><?php echo $long; ?></div>
							<?php
							if( !$abgeschl ) {
								?>
								<div class="col_buttons">
									<button type="submit" name="detail" class="edit_buttn" value="<?php echo $id ; ?>"></button>
									<button type="submit" name="del" class="delete_buttn" value="<?php echo $id ; ?>"></button>
									</div>
								<?php
								}
								?>
							</div>
						<?php
						$i++ ;
						}
						?>
					</div> <!-- main12_unten -->
				</form>
			</div><!-- main12 -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->