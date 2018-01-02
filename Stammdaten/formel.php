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

	$topic = 'Formeln' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;


	if( isset( $_POST[ 'save' ] ) ) {
		$frm		= $_POST[ 'frm' ] ;
		$frmel	= $_POST[ 'frmel' ] ;
		$rechne = $_POST[ 'rechne' ] ;
		if( $frm <> '' AND $frmel <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_form SET Form = '$frm', Formel = '$frmel', ZuRechnen = '$rechne' WHERE FormID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_form (`FormID`, `Form`, `Formel`, `ZuRechnen`, `Aktiv` ) VALUES( NULL, '$frm', '$frmel', '$rechne', true )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$errortxt = 'Formel gespeichert' ;
			$frm = '' ;
			$frmel = '' ;
			$rechne = '' ;
			}
		else {
			$errortxt = 'Form oder Anzeigen fehlt' ;
			}
		$chnge = false ;
		}

	if(  isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_form WHERE FormID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$_SESSION[ 'merkid' ] = $row->FormID ;
			$frm 		= $row->Form ;
			$frmel	= $row->Formel ;
			$rechne	= $row->ZuRechnen ;
			}
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_form SET Aktiv = 0 WHERE FormID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Formel gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		$frm = '' ;
		$frmel = '' ;
		$rechne = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

// Formeln laden
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_form WHERE Aktiv" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$formid 		= $row->FormID ;
		$form 			= $row->Form ;
		$formel 		= $row->Formel ;
		$zurechnen	= $row->ZuRechnen ;
		$liste [ ] = array( 'formid'=>$formid, 'form'=>$form, 'formel'=>$formel, 'zurechnen'=>$zurechnen ) ;
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
						<div class="col_160"><input type="text" name="frm" class="col_140" value="<?php echo $frm ; ?>"></div>
						<div class="col_270"><input type="text" name="frmel" class="col_250" value="<?php echo $frmel ; ?>"></div>
						<div class="col_420"><input type="text" name="rechne" class="col_400" value="<?php echo $rechne ; ?>"></div>
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
						<div class="col_160">Form</div>
						<div class="col_270">Formel in Anzeige</div>
						<div class="col_420r">Formel zum Rechnen</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$formid			= $liste [ $i ][ 'formid' ] ;
						$form				= $liste [ $i ][ 'form' ] ;
						$formel			= $liste [ $i ][ 'formel' ] ;
						$zurechnen	= $liste [ $i ][ 'zurechnen' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_160"><?php echo $form ; ?> </div>
							<div class="col_270"><?php echo $formel ; ?> </div>
							<div class="col_420"><?php echo $zurechnen ; ?> </div>
							<div class="col_buttons">
								<button type="submit" name="detail" class="edit_buttn" value="<?php echo $formid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $formid ; ?>"></button>
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