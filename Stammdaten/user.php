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
	$_SESSION[ 'navi' ] = 'Stammdat' ;

	$errortxt = '' ;
	$inputuser = false ;

	if( isset( $_POST[ 'neu' ] ) ) {  // neu
		$anrede 	= '' ;
		$titel_v	= '' ;
		$name			= '' ;
		$vorname	= '' ;
		$titel_h	= '' ;
		$jobtext1	= '' ;
		$jobtext2	= '' ;
		$email		= '' ;
		$mobil		= '' ;
		$userwww	= '' ;
		$pw				= '' ;
		$login		= '' ;
		$admini		= false ;
		$master		= false ;
		$pwreset	= true ;
		$gesperrt	= false ;
		$inputuser = true ;
		$usrid = 0  ;
		unset( $_POST[ 'neu' ] ) ;
		}
	elseif( isset( $_POST[ 'anders' ] ) ) {
		$useid = $_POST[ 'anders' ] ;
		unset( $_SESSION[ 'userid' ] ) ;
		$_SESSION[ 'userid' ] = $useid ;
		$abfrage = "SELECT * FROM $db_user WHERE UserID = '$useid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			include( '../php/get_db_user.php' ) ;
			$admini = $admin ;
			$logindate = date( 'd.m.Y', strtotime( $login ) );
			$inputuser = true ;
			}
		unset( $_POST[ 'anders' ] ) ;
		}
	elseif( isset( $_POST[ 'del' ] ) ) {
		$useid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_user SET Aktiv = 0 WHERE UserID = '$useid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST['delete'] ) ;
		$errortxt	= 'Anwender deaktiviert' ;
		$fx				= true ;
		$updliste = false;
		}
	elseif( isset( $_POST[ 'save' ] ) ) {
		include( '../php/get_input_user.php' ) ;
		if( isset( $_POST[ 'admini' ] ) ) { $admin = true ; } else { $admin = false ; }
		$useid = $_SESSION[ 'userid' ] ;

		if( $email != '' ) {
			if( $useid == 0 ) {
				$abfrage	= "INSERT INTO $db_user ( `UserID`, `FirmaBez`, `Anrede`, `Titel_vorne`, `Titel_hinten`, `Name`, `Vorname`, `eMail`, `Mobil`, `WWW`, `JobText1`, `JobText2`,
																						`Password`, `Admin`, `Funktion`, `master`, `pwReset`, `Gesperrt`, `Aktiv` )
											VALUES( NULL, '$firmabez', '$anrede', '$titel_v', '$titel_h', '$name', '$vorname', '$email', '$mobil', '$userwww', '$jobtext1', '$jobtext2', 
															'geh_heim','$admin', '$master', '$funktion', true, false, true )" ;
				}
			else {
				$abfrage = "UPDATE $db_user SET FirmaBez= '$firmabez', Anrede = '$anrede', Titel_vorne = '$titel_v', Titel_hinten = '$titel_h', Name = '$name', Vorname = '$vorname',
											eMail = '$email', Mobil = '$mobil', WWW = '$userwww', JobText1 = '$jobtext1', JobText2 = '$jobtext2', Admin = '$admin', Funktion = '$funktion', 
											master = '$master', pwReset = '$pwreset', Gesperrt = '$gesperrt'
											WHERE UserID = '$useid'" ;
				}
 			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
 			$usrid = 0 ;
			$inputuser = false ;
			unset( $_POST[ 'anders' ] ) ;
			}
		else {
			$inputuser = true ;
			$errortxt = 'E-Mail angeben' ;
			}
		unset( $_SESSION[ 'userid' ] ) ;
		unset( $_POST[ 'save' ] ) ;
		}
	else {
		}

	unset( $benutzer ) ;
	$benutzer = array( ) ;
	$abfrage = "SELECT UserID, Anrede, Name, Vorname, eMail FROM $db_user WHERE Aktiv ORDER BY Name" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$uid		= $row->UserID ;
		$anr		= $row->Anrede ;
		$nme		= $row->Name ;
		$vnme		= $row->Vorname ;
		$emehl	= $row->eMail ;
		$benutzer [ ] = array( 'uid'=>$uid, 'anr'=>$anr, 'nme'=>$nme, 'vnme'=>$vnme, 'emehl'=>$emehl ) ;
		}


//--------------------------------------------------------------------------------------

	include( "../php/head.php" ) ; 
	?>

		<div id="mainstammd">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >

				<div class="clear"></div>
				<div id="buttnbox">
					<?php
					if( $inputuser ) {
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
				if( !$inputuser ) {
					?>
					<div class="clear"></div>
					<div class="trhd">
						<div class="col_250">E-Mail</div>
						<div class="col_100">Anrede</div>
						<div class="col_180">Name</div>
						<div class="col_180">Vorname</div>
						</div>
					<?php
					$i = 0 ;
					$anz_benutzer = count( $benutzer ) ;
					while( $i < $anz_benutzer ) {
						$uid		= $benutzer [ $i ][ 'uid' ] ;
						$anr 		= $benutzer [ $i ][ 'anr' ] ;
						$nme		= $benutzer [ $i ][ 'nme' ] ;
						$vnme		= $benutzer [ $i ][ 'vnme' ] ;
						$emehl	= $benutzer [ $i ][ 'emehl' ] ;
						?>
						<div class="clear"></div>
						<div class="truser">
							<div class="col_250"><?php echo $emehl ; ?></div>
							<div class="col_100"><?php echo $anr ; ?></div>
							<div class="col_180"><?php echo $nme ; ?></div>
							<div class="col_180"><?php echo $vnme ; ?></div>
							<div >
								<button type="submit" name= "anders"	class="edit_buttn" value="<?php echo $uid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $uid ; ?>"></button>
								</div>
							</div>
						<?php
						$i++ ;
						}
					}	// display = 10 //
//----------------------------------------------------------------
				if( $inputuser ) {
					?>
					<div class="clear"></div>
					<div class="tr">
						<label for="name" class="col_130">Firma:</label>
						<?php
						echo '<select name="firmabez" size="1" class="col_400b">'; 
						$ab = "SELECT Bezeichnung FROM $db_firma WHERE Aktiv ORDER BY Bezeichnung" ;
						$eg = mysql_query( $ab ) OR die( "Error: $ab <br>". mysql_error() ) ;
						while( $rw = mysql_fetch_object( $eg ) ) {
							$was = $rw->Bezeichnung ;
							if( $was == $firmabez ) { 
								echo '<option selected value="' . $was . '">' . $was . '</option>' ;
								}
							else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
							} //while - array füllen
						echo'</select>';
						?>
						</div>


					<div class="clear"></div>
					<div class="tr">
						<label for="anrede" class="col_130">Anrede:</label>
						<input type="text" name="anrede" maxlength="25" class="col_400" value="<?php echo $anrede ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="titel_v" class="col_130">Titel, vorgestellt:</label>
						<input type="text" name="titel_v" maxlength="25" class="col_400" value="<?php echo $titel_v ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="name" class="col_130">Name:</label>
						<input type="text" name="name" maxlength="50" class="col_400" value="<?php echo $name ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="vorname" class="col_130">Vorname:</label>
						<input type="text" name="vorname" maxlength="50" class="col_400" value="<?php echo $vorname ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="titel_h" class="col_130">Titel, nachgestellt:</label>
						<input type="text" name="titel_h" maxlength="75" class="col_400" value="<?php echo $titel_h ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="jobtext1" class="col_130">Jobbezeichnung:</label>
						<input type="text" name="jobtext1" maxlength="75" class="col_400" value="<?php echo $jobtext1 ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<input type="text" name="jobtext2" maxlength="75" class="col_400a" value="<?php echo $jobtext2 ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="email" class="col_130">E-Mail:</label>
						<input type="email" name="email" class="col_400" value="<?php echo $email ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="mobil" class="col_130">Mobil:</label>
						<input type="tel" name="mobil" maxlength="25" class="col_400" value="<?php echo $mobil ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="userwww" class="col_130">WWW:</label>
						<input type="tel" name="userwww" maxlength="25" class="col_400" value="<?php echo $userwww ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label class="col_130">Funktion:</label>
						<fieldset class="radiobutt">
							<?php
							if( $funktion == 'Administrator' ) {
								?>
								<input type="radio" id="admin" name="funktion" value="Administrator" checked="checked">
								<?php
								}
							else {
								?>
								<input type="radio" id="admin" name="funktion" value="Administrator">
								<?php
								}
								?>
							<label for="admin"> Administrator</label> 

							<div class="clear"></div>
							<?php
							if( $funktion == 'Basiseingabe' ) {
								?>
								<input type="radio" id="basic" name="funktion" value="Basiseingabe" checked="checked">
								<?php
								}
							else {
								?>
								<input type="radio" id="basic" name="funktion" value="Basiseingabe">
								<?php
								}
								?>
							<label for="basic"> Basiseingabe</label>

							<div class="clear"></div>
							<?php
							if( $funktion == 'Feinjustierung' ) {
								?>
								<input type="radio" id="fine" name="funktion" value="Feinjustierung" checked="checked">
								<?php
								}
							else {
								?>
								<input type="radio" id="fine" name="funktion" value="Feinjustierung">
								<?php
								}
								?>
							<label for="fine"> Feinjustierung</label> 
							</fieldset>
						</div>
					<?php
					if( $useid != 0 ) {
						?>
						<div class="clear"></div>
						<div class="tr">
							<label for="pwreset" class="col_130">Passwort RESET:</label>
							<div class="radiobutt"><input type="checkbox" class="radiobutt" <?php if( $pwreset == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="pwreset" /></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<label for="gesperrt" class="col_130">Gesperrt:</label>
							<div class="radiobutt"><input type="checkbox" class="radiobutt" <?php if( $gesperrt == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="gesperrt" /></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<label for="login" class="col_130">Letzte Anmeldung:</label>
							<div class="radiobutt"><?php echo $logindate ; ?></div>
							</div>
						<?php
						}
					}	// $newuser OR $chguser
//----------------------------------------------------------------Admin, Basiseingabe, Feinjustierung
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