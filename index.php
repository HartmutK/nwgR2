<?php
	session_start( ) ;

	include( 'php/DBselect.php' ) ;
	include( 'php/GlobalProject.php' ) ;
	include( 'php/set_angemeldet.php' ) ;
	include( 'php/clear_sessions.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'index' ;

	if( isset( $_SESSION[ 'anmelden' ] ) ) {
		$anmelden = $_SESSION[ 'anmelden' ] ;
		}
	else {
		$_SESSION[ 'anmelden' ] = array() ;
		$anmelden = array() ;
		$anmelden = array( 'id'=>0, 'angemeldet'=>false, 'mail'=>'', 'adm'=>'0' ) ;
		$_SESSION[ 'anmelden' ] = $anmelden ;
		}
	$who = $anmelden[ 'id' ] ;
	$angemeldet = $anmelden[ 'angemeldet' ] ;
	$rolle = $anmelden[ 'adm' ] ;

	$topic = '' ;
	$errortxt = '' ;
	$eMail = '' ;
	$oldpwd = '' ;
	$pwd = '' ;
	$chkpwd = '' ;

	unset( $_SESSION[ 'admin' ] ) ;
	$_SESSION[ 'admin' ] = false ;
	
	if( isset( $_REQUEST[ 'seite' ] ) ) {
		$seite = $_REQUEST[ 'seite' ] ;
		$anabtxt = 'Abmelden' ;
		}
	else {
		$seite = 'Anmelden' ;
		$anabtxt = '' ;
		}

//--------------------------------------------------------------------------------------
	switch( $seite ) {
		case "Anmelden":
			$display = 10 ;
			clear_sessions() ;
			if( isset( $_POST[ 'sb2' ] ) ) {
				$display = 12 ;
				}
			elseif(  isset( $_POST[ 'sb1' ] ) ) {
				$eMail = $_POST[ 'eMail' ] ;
				$abfrage = "SELECT * FROM $db_user WHERE eMail = '$eMail'" ;
				$ergebnis = mysql_query( $abfrage ) ;
				$row = mysql_num_rows( $ergebnis ) ;
				if( $row > 0 ) {
					$ergebnis = mysql_query( $abfrage ) ;
					while( $row = mysql_fetch_object( $ergebnis ) ) {
						if( $row->pwReset ) {
							$errortxt = 'Passwort nach zur&uuml;cksetzen bitte &auml;ndern.' ;
							$display = 14 ;
							}
						else {
							$Gesperrt = $row->Gesperrt ;
							if( $Gesperrt ) {
									$errortxt = 'Benutzung gesperrt.' ;
									$display = 10 ;
								}
							else {
								$pwd = $row->Password ;
//								$pword = md5( $_POST[ 'password' ] ) ;
								$pword = $_POST[ 'password' ] ;
								if( $pwd != $pword ) {
									$errortxt = 'Passwort nicht korrekt.' ;
									$display = 10 ;
									}
								else {
									$errortxt = '' ;
									$who = $row->UserID ;
									$Admin = $row->Admin ;
									$Vorname = $row->Vorname ;
									$Name = $row->Name ;
									$eMail = $row->eMail ;
									$tag = time() ;
									$login = date( "Y-m-d", $tag ) ;
									$abfrage = "UPDATE $db_user SET LastLogin = '$login' WHERE UserID = '$who'" ;
									$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
									if( $ergebnis == true ) {
										$errortxt = '' ;
										$display = set_angemeldet( $who, $eMail, $Admin ) ;
										}
									else {
										$errortxt = 'Fehler bei Anmeldung.' ;
										}
									}
								}
							}
						}
					}
				else {
					$errortxt = 'E-Mail Adresse unbekannt.' ;
					}
				}
			break ;
//--------------------------------------------------------------------------------------
		case "forgot_pw":
			$display = 12 ;
			if( isset( $_POST[ 'eMail' ] ) ) { 
				$eMail = $_POST[ 'eMail' ] ;
				$abfrage = "SELECT * FROM $db_user WHERE eMail = '$eMail'" ;
				$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				$row = mysql_num_rows( $ergebnis ) ;
				if( $row > 0 ) {
					$ID = $row->UserID ;
					$pwl = 4 ;
					$bytes = mt_rand( 1000, 9999 ) ;
					$newpw = bin2hex( $bytes ) ;
//					$newpass = md5( bin2hex( $bytes ) ) ;
					$newpass = bin2hex( $bytes ) ;
					$aendern = "UPDATE $db_user SET Password = '$newpass', pwReset = 'true' WHERE eMail = '$eMail'" ;
					$update = mysql_query($aendern);
					$eMail_to = $eMail ; 
					$eMail_subject = 'Passwort zurückgesetzt' ;
					$eMail_message = "Ihr Passwort lautet: '" . $newpw ."'. \n\n Bitte ändern Sie dies nach ihrer nächsten Anmeldung." ;
					$eMail_from = 'From: Reset <leocar@leocar>' .
//				eMail($eMail_to, $eMail_subject, $eMail_message, $eMail_from) ; 
					$errortxt = 'Ein E-eMail mit Ihrem Passwort ' . $newpw . ' wurde an die angegebene Adresse versendet.' ;
					$display = 14 ;
					}
				else {
					$errortxt = 'Die angegebene E-eMail Adresse ist unbekannt. Bitte pr&uuml;fen oder neu registrieren!' ;
					}
				}
			else {
				$errortxt = 'Bitte eine E-Mail Adresse angeben!' ;
				}
			break ;
//--------------------------------------------------------------------------------------
		case "change_pw":
			$display = 14 ;
			if( isset( $_POST[ 'sb1' ] ) ) {
				$eMail = $_POST[ 'eMail' ] ;
				$abfrage = "SELECT * FROM $db_user WHERE eMail = '$eMail'" ;
				$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				$row = mysql_fetch_object( $ergebnis ) ;
				$who = $row->UserID ;
				$Gesperrt = $row->Gesperrt ;
				if( $Gesperrt ) {
					$errortxt = 'Benutzung gesperrt.' ;
					$display = 10 ;
					}
				else {
					$Admin = $row->Admin ;
					$Vorname = $row->Vorname ;
					$Name = $row->Name ;
					$eMail = $row->eMail ;
					$oldpw = $row->Password ;
//					$pword = md5( $_POST[ 'password' ] ) ;
					$pword = $_POST[ 'password' ] ;
					if( $pword != $oldpw ) {
						$errortxt = 'Die Eingabe des alten Passwortes ist nicht korrekt' ;
						}
					else { 
						$pword = $_POST[ 'newpw' ] ;
						$chkpwd = $_POST[ 'chkpw' ] ;
						if( $pword != $chkpwd ) {
							$errortxt = 'Passworteingaben sind ungleich.' ;
							}
						else {
//							$newpass = md5( $pword ) ;
							$login = date( "d.m.Y" ) ;
							$abfrage = "UPDATE $db_user SET Password = '$newpass', pwReset = 'true' , LastLogin = '$login' WHERE eMail = '$eMail'" ;
							$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
							if( $ergebnis == true ) {
								$errortxt = 'Passwort erfolgreich ge&auml;ndert.' ;
								$display = set_angemeldet( $who, $eMail, $Admin ) ;
								}
							else {
								$errortxt = 'Fehler bei Passwort&auml;nderung.' ;
								}
							}
						}
					}
				}
			break ;
//--------------------------------------------------------------------------------------
		case "admin":
			$display = 20 ;
			break ;
//--------------------------------------------------------------------------------------
		case "gutachten":
			$display = 40 ;
			break ;
//--------------------------------------------------------------------------------------
		case "Abmelden":
			$display = 99 ;
			break ;
//--------------------------------------------------------------------------------------
		default:
			$display = 10 ;
			break ;
		}  // switch $seite
//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------
// $display =  10	 -> Anmelden 								/ Anmelden
// $display =  12	 		-> Passwort vergessen 	/ forgot_pw
// $display =  14	 		-> Passwort ändern 			/ change_pw
// $display =  20	 -> System Admin						/ details
// $display =  40	 -> Gutachten							/ admin
// $display =  99	 -> Abmelden

// else, display = 10

	switch( $display ) {
		case 12 :
			$topic = 'Passwort vergessen' ;
			$seite = 'forgot_pw' ;
			$button1 = 'Neues Passwort' ;
			$button2 = '' ;
			break ;
		case 14 :
			$topic = 'Passwort &auml;ndern' ;
			$seite = 'change_pw' ;
			$button1 = 'Passwort speichern' ;
			$button2 = '' ;
			break ;
		case 20 :
			$topic = 'System verwalten' ;
			$seite = 'admin' ;
			$button1 = '' ;
			$button2 = '' ;
			break ;
		case 40 :
			$topic = 'Gutachten bearbeiten' ;
			$seite = 'gutachten' ;
			$button1 = '' ;
			$button2 = '' ;
			break ;
		default:
			$display = 10 ;
			$topic = 'Anmeldung' ;
			$seite = 'Anmelden' ;
			$button1 = 'Anmelden' ;
			$button2 = 'Passwort vergessen' ;
			break ;
		}  // switch $display

	unset( $_SESSION[ 'anabmeld' ] ) ;
	if( $display == 10 OR $display == 12 OR $display == 14 ) {
		$_SESSION[ 'anabmeld' ] = true ;
		}
	else {
		$_SESSION[ 'anabmeld' ] = false ;
		}
?>
<?php 
include( "php/head.php" ) ;
?>

			<div id="mainstammd">

				<form method="post" name='input_fields' class="login" enctype="multipart/form-data" >
					<input type="hidden" name="seite" value='<?php echo $seite ; ?>' />

					<?php 
//----------------------------------------------------------------
					if( $display == 10 OR $display == 12 OR $display == 14 ) {
						?>
						<label for="eMail" class="input_label">E-Mail:</label>
						<input type="eMail" class="input_item" name="eMail" value='<?php echo $eMail ; ?>'/>
						<?php 
						if( $display != 12 ) {
							?>
							<label for="password" class="input_label">Passwort:</label>
							<input type="password" class="input_item" name="password" value=''/>
							<?php
							}
						if( $display == 14 ) {
							?>
							<label for="password" class="input_label">Neues Passwort:</label>
							<input type="password" class="input_item" name="newpw" id="newpw" value=''/>
							<label for="password" class="input_label">Passwortkontrolle:</label>	
							<input type="password" class="input_item" name="chkpw" id="chkpw" value=''/>
							<?php
							}
						}
						else {
							$anabmeld = false ;
						}					// display = 10/12/14
//-----------------------------------  ADMIN  -----------------------------
					if( $display == 20 ) {
						?>
						<table>
							<tr class="buttnrowhead">
								<td>A. Allgemeines</td>
								</tr>
							<tr class="buttnrow">
								<td class="buttntd"><a href="Stammdaten/indizes.php" class="battn">A  Indizes</a></td>
								<td class="buttntd"><a href="Stammdaten/lpo.php" class="battn">A  PLZ/Orte</a></td>
								<td class="buttntd"><a href="Stammdaten/kastralgemeinden.php" class="battn">A  Kastralgemeinden</a></td>
								<td class="buttntd"><a href="Stammdaten/gutachtenzweck.php" class="battn">A.3  Gutachtenzweck</a></td>
								</tr>
							<tr class="buttnrowhead">
								<td>B. Befund</td>
								</tr>
							<tr class="buttnrow">
								<td class="buttntd"><a href="Stammdaten/gebaeudeart.php" class="battn">B.2.2  Geb&auml;udearten</a></td>
								<td class="buttntd"><a href="Stammdaten/bauweise.php" class="battn">B.2.2  Bauweisen</a></td>
								<td class="buttntd"><a href="Stammdaten/objektzustaende.php" class="battn">B.2.2  Objektzust&auml;nde</a></td>
								<td class="buttntd"><a href="Stammdaten/widmungen.php" class="battn">B.3  Widmungen</a></td>
								<td class="buttntd"><a href="Stammdaten/wohnungslage.php" class="battn">B.3  Lagen</a></td>
								<td class="buttntd"><a href="Stammdaten/we_objektarten.php" class="battn">B.3  WE-Objektarten</a></td>
								<td class="buttntd"><a href="Stammdaten/wohnungnr.php" class="battn">B.3  Objektnummern</a></td>
								<td class="buttntd"><a href="Stammdaten/raumarten.php" class="battn">B.4  Raumarten</a></td>
								<td class="buttntd"><a href="Stammdaten/raumanmerkungen.php" class="battn">B.4  R&auml;ume, Anmerkungen</a></td>
								<td class="buttntd"><a href="Stammdaten/formel.php" class="battn">B.4  Formeln</a></td>
								<td class="buttntd"><a href="Stammdaten/gesetzliches.php" class="battn">B.5.1  Gesetzliche Grundlagen</a></td>
								<td class="buttntd"><a href="Stammdaten/empfehlungen.php" class="battn">B.5.2  Empfehlungen</a></td>
								<td class="buttntd"><a href="Stammdaten/baukonsens.php" class="battn">B.5.3  Baubeh&ouml;rdlicher Konsens</a></td>
								<td class="buttntd"><a href="Stammdaten/vertraege.php" class="battn">B.5.4  Vertr&auml;ge, Vereibarungen</a></td>
								<td class="buttntd"><a href="Stammdaten/gut8en.php" class="battn">B.5.5  Gutachten, Entscheide</a></td>
								<td class="buttntd"><a href="Stammdaten/sonstigedoku.php" class="battn">B.5.6  Sonstige Dokumente</a></td>
								</tr>
							<tr class="buttnrowhead">
								<td>C. Gutachten</td>
								</tr>
							<tr class="buttnrow">
								<td class="buttntd"><a href="Stammdaten/gutachtentexte.php" class="battn">C.1/2  Gutachtentexte</a></td>
								<td class="buttntd"><a href="Stammdaten/allgflache.php" class="battn">C.2  Allgemeinfl&auml;chen</a></td>
								<td class="buttntd"><a href="Stammdaten/zubehoer.php" class="battn">C.5  Zubeh&ouml;r</a></td>
								<td class="buttntd"><a href="Stammdaten/zuschlaege.php" class="battn">C.6  Zuschl&auml;ge</a></td>
								<td class="buttntd"><a href="Stammdaten/rechtspfleger.php" class="battn">C.5/6  Rechtspflegertexte</a></td>
								<td class="buttntd"><a href="Stammdaten/zu_abschlag.php" class="battn">C.7  Zu-/Abschl&auml;ge</a></td>
								<td class="buttntd"><a href="Stammdaten/abkuerzungen.php" class="battn">C.12  Abk&uuml;rzungen</a></td>
								</tr>
							<tr class="buttnrowhead">
								<td>Verwaltung</td>
								</tr>
							<tr class="buttnrow">
								<td class="buttntd"><a href="Stammdaten/user.php?seite=anfang" class="battn">Benutzer</a></td>
								<td class="buttntd"><a href="Stammdaten/firma.php" class="battn">Firmendaten</a></td>
								<td class="buttntd"><a href="Stammdaten/system.php" class="battn">Druckeinstellungen</a></td>
								</tr>
							<tr class="buttnrow">
								<td class="buttntd"><a href="Stammdaten/wohnungslage_zuab.php" class="battn">B.3  Lageabh. Zu-/Abschl&auml;ge</a></td>
								<td class="buttntd"><a href="Stammdaten/einheiten.php" class="battn">C.5/6  Einheiten</a></td>
								<td class="buttntd"><a href="Stammdaten/zuablagen.php" class="battn">C.7  Zu-/Abschlagsgruppen</a></td>
								</tr>
							</table>
						<?php
						}	// display = 20 //
//-----------------------------------  Gutachten  -----------------------------
					if( $display == 40 ) {
						?>
					  <script language="javascript">location.replace("Gutachten/gutachten.php?seite=anfang1")</script>
						<?php
						}	// display = 40 //
//----------------------------------------------------------------
						?>

				<div id="buttnbox">
					<?php
					if( $button1 != '' ) {
						?>
						<button type="submit" Name="sb1" class="battn"><?php echo $button1 ; ?></button>
						<?php
						}
					if( $button2 != '' ) {
						?>
						<button type="submit" Name="sb2" class="battn"><?php echo $button2 ; ?></button>
						<?php
						}
						?>
					</div>	<!-- buttnbox -->
					</form>

				<div class="errmsg"><?php echo $errortxt ; ?></div>

				</div>  <!-- main -->
			</div>  <!-- container -->
		</body>
	</html>
<!-- EOF -->