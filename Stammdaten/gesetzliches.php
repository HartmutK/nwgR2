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

	$topic = 'Gesetzliche Grundlagen' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;


	if( isset( $_POST[ 'save' ] ) ) {
		$gesetzl = $_POST[ 'gesetzl' ] ;
		$txt = $_POST[ 'txt' ] ;
		$chnge = false ;
		if( isset( $_POST[ 'automatik' ] ) ) { $deflt = true ; } else { $deflt = false ; }
		if( $gesetzl <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'merkid' ] ;
				$abfrage = "UPDATE $db_ugsource SET Beschreibg = '$gesetzl', Txt = '$txt', Deflt = '$deflt' WHERE UGID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_ugsource (`UGID`, `Gruppe`, `Beschreibg`, `Txt`, `Deflt`, `Aktiv` ) VALUES( NULL, 1, '$gesetzl', '$txt', '$deflt', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'Grundlage gespeichert' ;
			$gesetzl = '' ;
			$txt = '' ;
			$deflt = false ;
			}
		else {
			$errortxt = 'Grundlage fehlt' ;
			}
		$chnge = false ;
		}
	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}
	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_ugsource WHERE UGID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$_SESSION[ 'merkid' ] = $row->UGID ;
			$gesetzl	= $row->Beschreibg ;
			$txt	= $row->Txt ;
			$deflt	= $row->Deflt ;
			$chnge	= true ;
			}
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_ugsource SET Aktiv = 0 WHERE UGID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Grundlage gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$gesetzl = '' ;
		$txt = '' ;
		$deflt = false ;
		}

// Formeln laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 1 ORDER BY Deflt DESC, Beschreibg" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$gesid = $row->UGID ;
		$ges	 = $row->Beschreibg;
		$txtl	 = $row->Txt ;
		$def	 = $row->Deflt ;
		$liste [ ] = array( 'gesid'=>$gesid, 'ges'=>$ges, 'txtl'=>$txtl, 'def'=>$def ) ;
		}
	$_SESSION[ 'liste' ] = $liste ;
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben2">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_80"><input type=checkbox name="automatik" <?php if( $deflt == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?>/></div>
						<div class="col_420"><input type="text" name="gesetzl" class="col_400" value="<?php echo $gesetzl ; ?>"></div>
						<div class="col_620"><textarea name="txt" maxlength="64000" cols="122" rows="13" class="col_600" ><?php echo $txt ; ?></textarea></div>
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
						<div class="col_80">Automatisch</div>
						<div class="col_420">Grundlage</div>
						<div class="col_420">Text</div>
					</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten2">
				<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$gesid	= $liste [ $i ][ 'gesid' ] ;
						$ges		= $liste [ $i ][ 'ges' ] ;
						$txtl		= $liste [ $i ][ 'txtl' ] ;
						$def		= $liste [ $i ][ 'def' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_80"><input type=checkbox disabled <?php if( $def == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>
							<div class="col_420"><?php echo $ges; ?></div>
							<div class="col_620"><textarea disabled maxlength="64000" cols="122" rows="13" class="col_600" ><?php echo $txtl ; ?></textarea></div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $gesid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $gesid ; ?>"></button>
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