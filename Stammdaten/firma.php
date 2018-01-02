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
	$topic = 'Firmendaten verwalten' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'Stammdat' ;

	$errortxt = '' ;

	$inputuser = false ;

	if( isset( $_POST[ 'neu' ] ) ) {  // neu
		$fabez		= '' ;
		$fastr		= '' ;
		$faplz		= '' ;
		$faort		= '' ;
		$fatel		= '' ;
		$email		= '' ;
		$famail		= '' ;
		$fawww		= '' ;
		$firmennr	= '' ;
		$fauid		= '' ;
		$inputuser = true ;
		$useid = 0  ;
		unset( $_POST[ 'neu' ] ) ;
		}
	elseif( isset( $_POST[ 'anders' ] ) ) {
		$useid = $_POST[ 'anders' ] ;
		unset( $_SESSION[ 'userid' ] ) ;
		$_SESSION[ 'userid' ] = $useid ;
		$abfrage = "SELECT * FROM $db_firma WHERE FaID = '$useid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			include( '../php/get_db_firma.php' ) ;
			$town = $faort ;
			$inputuser = true ;
			}
		unset( $_POST[ 'anders' ] ) ;
		}
	elseif( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_firma SET Aktiv = 0 WHERE FaID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		unset( $_POST['delete'] ) ;
		$errortxt		= 'Firma deaktiviert' ;
		$fx					= true ;
		}
	elseif( isset( $_POST[ 'save' ] ) ) {
		include( '../php/get_input_firma.php' ) ;
		$useid = $_SESSION[ 'userid' ] ;

			if( $fabez != '' AND $fastr != '' AND $faplz != '' ) {
			$town	= '' ;
			$abfrage = "SELECT * FROM $db_lpo WHERE PLZ = '$faplz'" ;
			$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			while( $row = mysql_fetch_object( $ergeb ) ) {
				$town = $row->Ort ;
				}
			if( $useid == 0 ) {
				$abfrage	= "INSERT INTO $db_firma ( `FaID`, `Bezeichnung`, `Str`, `PLZ`, `Ort`, `Tel`, `eMail`, `WWW`, `FN`, `UID`, `Aktiv` )
											VALUES( NULL, '$fabez', '$fastr', '$faplz', '$town', '$fatel', '$famail', '$fawww', '$firmennr', '$fauid', true )" ;
				}
			else {
				$abfrage = "UPDATE $db_firma SET Bezeichnung = '$fabez', Str = '$fastr', PLZ= '$faplz',
											Ort = '$town', Tel = '$fatel', eMail = '$famail', WWW = '$fawww', FN = '$firmennr', UID = '$fauid'
											WHERE FaID = '$useid'" ;
				}
 			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
 			$usrid = 0 ;
			$inputuser = false ;
			}
		else {
			$errortxt = 'Firmenbezeichnung, Stra&szlig;e oder PLZ ist nicht angegeben!' ;
			$inputuser = true ;
			}
		unset( $_SESSION[ 'userid' ] ) ;
		unset( $_POST[ 'save' ] ) ;
		}
	else {
		}

	unset( $firma ) ;
	$firma = array( ) ;
	$abfrage = "SELECT FaID, Bezeichnung, Str, PLZ, Ort FROM $db_firma WHERE Aktiv ORDER BY Bezeichnung" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$faid		= $row->FaID ;
		$fabz		= $row->Bezeichnung ;
		$fast		= $row->Str ;
		$fastadt	= $row->PLZ . ' ' . $row->Ort ;
		$firma [ ] = array( 'faid'=>$faid, 'fabz'=>$fabz, 'fast'=>$fast, 'fastadt'=>$fastadt ) ;
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
						<div class="col_180">Firmenbezeichnung</div>
						<div class="col_180">Stra&szlig;e</div>
						<div class="col_180">PLZ / Ort</div>
						</div>
					<?php
					$i = 0 ;
					$anz_firma = count( $firma ) ;
					while( $i < $anz_firma ) {
						$faid			= $firma [ $i ][ 'faid' ] ;
						$fabz			= $firma [ $i ][ 'fabz' ] ;
						$fast			= $firma [ $i ][ 'fast' ] ;
						$fastadt	= $firma [ $i ][ 'fastadt' ] ;
						?>
						<div class="clear"></div>
						<div class="truser">
							<div class="col_180"><?php echo $fabz ; ?></div>
							<div class="col_180"><?php echo $fast ; ?></div>
							<div class="col_180"><?php echo $fastadt ; ?></div>
							<div>
								<button type="submit" name= "anders"	class="edit_buttn" value="<?php echo $faid ; ?>"></button>
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $faid ; ?>"></button>
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
						<label for="anrede" class="col_120">Bezeichnung</label>
						<input type="text" name="fabez" maxlength="50" class="col_400" value="<?php echo $fabez; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="titel_v" class="col_120">Stra&szlig;e</label>
						<input type="text" name="fastr" maxlength="50" class="col_400" value="<?php echo $fastr ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="name" class="col_120">PLZ / Ort</label>
						<?php
						echo '<select name="faplz" size="1" class="col_80">'; 
						$abfrage = "SELECT * FROM $db_lpo ORDER BY PLZ" ;
						$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						while( $row = mysql_fetch_object( $ergeb ) ) {
							$was = $row->PLZ ;
							if( $was == $faplz ) { 
								echo '<option selected value="' . $was . '">' . $was . '</option>' ;
								$town = $row->Ort ;
								}
							else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
							} //while - array füllen
						echo'</select>';
						?>
						<input type="text" disabled class="col_270" value="<?php echo $town ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="vorname" class="col_120">Telefon</label>
						<input type="text" name="fatel" maxlength="25" class="col_400" value="<?php echo $fatel ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="titel_h" class="col_120">E-Mail</label>
						<input type="email" name="famail" maxlength="50" class="col_400" value="<?php echo $famail ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="jobtext1" class="col_120">WWW</label>
						<input type="text" name="fawww" maxlength="50" class="col_400" value="<?php echo $fawww ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="mobil" class="col_120">Firmenbuch</label>
						<input type="text" name="firmennr" maxlength="25" class="col_400" value="<?php echo $firmennr ; ?>">
						</div>
					<div class="clear"></div>
					<div class="tr">
						<label for="userwww" class="col_120">UID</label>
						<input type="text" name="fauid" maxlength="25" class="col_400" value="<?php echo $fauid ; ?>">
						</div>
						<?php
					}	// $newuser OR $chguser
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