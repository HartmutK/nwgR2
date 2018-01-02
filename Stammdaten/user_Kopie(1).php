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
	$topic = 'Benutzerdaten verwalten' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'Stammdaten' ;

	$errortxt = '' ;

	unset( $_SESSION[ 'benutzer' ] ) ;
	$_SESSION[ 'benutzer' ] = array() ;
	$benutzer = array( ) ;
	$abfrage = "SELECT * FROM $db_user" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_user.php' ) ;
		$benutzer [ ] = array( 'userid'=>$userid, 'firmaid'=>$firmaid, 'anrede'=>$anrede, 'titel_v'=>$titel_v, 'titel_h'=>$titel_h, 'name'=>$name, 
														'vorname'=>$vorname, 'email'=>$email, 'pw'=>$pw, 'www'=>$userwww, 'login'=>$login, 'admin'=>$admin, 'pwreset'=>$pwreset, 'gesperrt'=>$gesperrt ) ;
		}

	$_SESSION[ 'benutzer' ] = $benutzer ;

	if( isset( $_REQUEST[ 'seite' ] ) ) {
		$sdseite = $_REQUEST[ 'seite' ] ;
		}
	else {
		$sdseite = 'anfang' ;
		}

	switch( $sdseite ) {
		case "anfang":
			if( isset( $_POST[ 'neu' ] ) ) {  // neu
				$anrede 	= '' ;
				$titel_v	= '' ;
				$titel_h	= '' ;
				$name			= '' ;
				$vorname	= '' ;
				$email		= '' ;
				$pw				= '' ;
				$login		= '' ;
				$admin		= false ;
				$master		= false ;
				$pwreset	= true ;
				$gesperrt	= false ;
				$display	= 20 ;
				}
			elseif(  isset( $_POST[ 'back' ] ) ) {  // zurück
				$display = 10 ;
				}
			elseif(  isset( $_POST[ 'anders' ] ) ) {
				$userid = $_POST[ 'anders' ] ;
				$abfrage = "SELECT * FROM $db_user WHERE UserID = '$userid'" ;
				$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergebnis ) ) {
					include( '../php/get_db_user.php' ) ;
					unset( $_SESSION[ 'userid' ] ) ;
					unset( $_SESSION[ 'firmaid' ] ) ;
					$_SESSION[ 'userid' ] = $userid ;
					$_SESSION[ 'firmaid' ] = $firmaid ;
					$logindate = date( 'd.m.Y', strtotime( $login ) );
					}
				$display = 30 ;
				}
			else {
				$display = 10 ;
				}
			break ;
//--------------------------------------------------------------------------------------
		case "new":
			if( isset( $_POST[ 'save' ] ) ) {
				$display	= 20 ;
				if( $email != '' ) {
					include( '../php/get_input_user.php' ) ;
					$abfrage	= "INSERT INTO $db_user ( `UserID`, `FirmaID`, `Anrede`, `Titel_vorne`, `Titel_hinten`, `Name`, `Vorname`, `eMail`, `Mobil`, `WWW`, `JobText1`, `JobText2`,
																							`Password`, `Admin`, `master`, `pwReset`, `Gesperrt` )
												VALUES( NULL, '$firmaid', '$anrede', '$titel_v', '$titel_h', '$name', '$vorname', '$email', '$mobil', '$userwww', '$jobtext1', '$jobtext2', 
																'geh_heim','$admin', '$master', false, false )" ;
					$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
					}
				else {
					$errortxt = 'E-Mail angeben' ;
					}
				}
			elseif(  isset( $_POST[ 'back' ] ) ) {
				$display = 10 ;
				}
			else {
				$display = 20 ;
				}
			break ;

//--------------------------------------------------------------------------------------
		case "chg":
			if( isset( $_POST[ 'save' ] ) ) {
				$display = 30 ;
				if( $email != '' ) {
					$userid = $_SESSION[ 'userid' ] ;
					include( '../php/get_input_user.php' ) ;
					$abfrage = "UPDATE $db_user SET Anrede = '$anrede', Titel_vorne = '$titel_v', Titel_hinten = '$titel_h', Name = '$name', Vorname = '$vorname',
												eMail = '$email', Mobil = '$mobil', WWW = '$userwww', JobText1 = '$jobtext1', JobText2 = '$jobtext2', Admin = '$admin', 
												master = '$master', pwReset = '$pwreset', Gesperrt = '$gesperrt'
												WHERE UserID = '$userid'" ;
					$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
					}
				else {
					$errortxt = 'E-Mail angeben' ;
					}
				}
			elseif(  isset( $_POST[ 'back' ] ) ) {
				$display = 10 ;
				}
			else {
				$display = 30 ;
				}
			break ;
//--------------------------------------------------------------------------------------
		default:
			$display = 10 ;
			break ;
		}  // switch $seite

//--------------------------------------------------------------------------------------

	switch( $display ) {
		case 20 :
			$sdseite	= 'new' ;
			break ;
		case 30 :
			$sdseite	= 'chg' ;
			break ;
		default:
			$sdseite	= 'anfang' ;
			break ;
		}  // switch $display
?>

<?php include( "../php/head.php" ) ; ?>

		<div id="mainstammd">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
			<input type="hidden" Name="sdseite" value='<?php echo $sdseite ; ?>' />

			<div id="buttnbox">
				<?php
				if( $display != 10 ) {
					?>
					<button type="submit" Name="save" class="okay_buttn"></button>
					<button type="submit" Name="back" class="back_buttn"></button>
					<?php
					}
				else {
					?>
					<button type="submit" Name="neu" class="neu_buttn"></button>
					<a href="../index.php?seite=admin" class="back_buttn"></a>
					<?php
					}
					?>
				<div class="errmsg"><?php echo $errortxt ; ?></div>
				</div> <!-- buttnbox -->

			<?php
//----------------------------------------------------------------
			if( $display == 10 ) {
				?>
				<table>
					<tr class="tabrowhead">
						<td class="td150">E-Mail</td>
						<td class="td100">Anrede</td>
						<td class="td150">Name</td>
						<td class="td150">Vorname</td>
						</tr>
					</table>
				<?php
				$i = 0 ;
				$benutzer = $_SESSION[ 'benutzer' ] ;
				$anz_benutzer = count( $benutzer ) ;
				while( $i < $anz_benutzer ) {
					$userid 	= $benutzer [ $i ][ 'userid' ] ;
					$anrede 	= $benutzer [ $i ][ 'anrede' ] ;
					$titel_v	= $benutzer [ $i ][ 'titel_v' ] ;
					$name			= $benutzer [ $i ][ 'name' ] ;
					$vorname	= $benutzer [ $i ][ 'vorname' ] ;
					$titel_h	= $benutzer [ $i ][ 'titel_h' ] ;
					$email		= $benutzer [ $i ][ 'email' ] ;
					$login		= $benutzer [ $i ][ 'login' ] ;
					$admin		= $benutzer [ $i ][ 'admin' ] ;
					$pwreset	= $benutzer [ $i ][ 'pwreset' ] ;
					$gesperrt	= $benutzer [ $i ][ 'gesperrt' ] ;
					?>
					<table>
						<tr class="tabrow">
							<td class="td150"><?php echo $email ; ?></td>
							<td class="td100"><?php echo $anrede ; ?></td>
							<td class="td150"><?php echo $name ; ?></td>
							<td class="td150"><?php echo $vorname ; ?></td>
							<td><button type="submit" name= "anders"	class="edit_buttn" value="<?php echo $userid ; ?>"></button></td>
							</tr>
						</table>
					<?php
					$i++ ;
					}
				}	// display = 10 //
//----------------------------------------------------------------
			if( $display == 20 OR $display == 30) {
				?>
				<label for="anrede" class="input_label">Anrede</label>
				<input type="text" name="anrede" maxlength="25" class="input_item" value="<?php echo $anrede ; ?>">
				<label for="titel_v" class="input_label">Titel, vorgestellt</label>
				<input type="text" name="titel_v" maxlength="25" class="input_item" value="<?php echo $titel_v ; ?>">
				<label for="name" class="input_label">Name</label>
				<input type="text" name="name" maxlength="50" class="input_item" value="<?php echo $name ; ?>">
				<label for="vorname" class="input_label">Vorname</label>
				<input type="text" name="vorname" maxlength="50" class="input_item" value="<?php echo $vorname ; ?>">
				<label for="titel_h" class="input_label">Titel, nachgestellt</label>
				<input type="text" name="titel_h" maxlength="75" class="input_item" value="<?php echo $titel_h ; ?>">
				<label for="jobtext1" class="input_label">Jobbezeichnung</label>
				<input type="text" name="jobtext1" maxlength="75" class="input_item" value="<?php echo $jobtext1 ; ?>">
				<label for="jobtext2" class="input_label"></label>
				<input type="text" name="jobtext2" maxlength="75" class="input_item" value="<?php echo $jobtext2 ; ?>">
				<label for="email" class="input_label">E-Mail</label>
				<input type="email" name="email" class="input_item" value="<?php echo $email ; ?>">
				<label for="mobil" class="input_label">Mobil</label>
				<input type="tel" name="mobil" maxlength="25" class="input_item" value="<?php echo $mobil ; ?>">
				<label for="userwww" class="input_label">WWW</label>
				<input type="tel" name="mobil" maxlength="25" class="input_item" value="<?php echo $userwww ; ?>">
				<label for="admin" class="input_label">Administrator</label>
				<input type="checkbox" class="input_item" <?php if( $admin == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="admin" />
				<label for="master" class="input_label">Superuser</label>
				<input type="checkbox" class="input_item" <?php if( $master == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="master" />
				<label for="pwreset" class="input_label">Passwort RESET</label>
				<input type="checkbox" class="input_item" <?php if( $pwreset == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="pwreset" />
				<label for="gesperrt" class="input_label">Gesperrt</label>
				<input type="checkbox" class="input_item" <?php if( $gesperrt == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="gesperrt" />
				<label for="login" class="input_label">Letzte Anmeldung</label>
				<div class="input_item"><?php echo $logindate ; ?></div>
				<?php
				}	// display = 20 OR 30 //
//----------------------------------------------------------------
				?>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->