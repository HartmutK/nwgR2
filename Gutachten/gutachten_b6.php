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
	$_SESSION[ 'navi' ] = 'B.6' ;

	$bereich = 'Plangrundlagen' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = '$gutachtenid'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	$weg = '../upload/' . $gutachtenid ;

	if( !file_exists( $weg ) ) {
		mkdir( $weg, 0777, true) ;
		}

	$weg = $weg . '/' ;

//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------
	if(isset($_POST['submit'])){
		if(count($_FILES['upload']['name']) > 0){
			//Loop through each file

			for($i=0; $i<count($_FILES['upload']['name']); $i++) {
				//Get the temp file path
				$tmpFilePath = $_FILES['upload']['tmp_name'][$i] ;

				//Überprüfung dass das Bild keine Fehler enthält
				if( function_exists( 'exif_imagetype' ) ) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
					$allowed_types = array( IMAGETYPE_PNG, IMAGETYPE_JPG, IMAGETYPE_JPEG, IMAGETYPE_GIF ) ;
					$detected_type = exif_imagetype( $_FILES['upload']['tmp_name'][$i] ) ;
					if( !in_array( $detected_type, $allowed_types ) ) {
						$errortxt = 'Nur der Upload von Bilddateien im Format png, jpg, jpeg oder gif ist gestattet.' ;
						$no_err = false ;
						}
					else {
						$no_err = true ;
						}
					}

				//Make sure we have a filepath
				if( $no_err AND $tmpFilePath != "" ) {
            
					//save the filename
					$shortname = $_FILES['upload']['name'][$i] ;

					//save the url and the file
					$filePath = $weg . $_FILES['upload']['name'][$i] ;

					//Upload the file into the temp dir
					if( move_uploaded_file( $tmpFilePath, $filePath ) ) {
						$files[] = $shortname ;
						//insert into db 
						//use $shortname for the filename
						//use $filePath for the relative url to the file
						}  // if( move
					}  // if( $no_err

					$bezeichng		= $_POST[ 'bezeichng' ] ;
					$beschreibung	= $_POST[ 'beschreibung' ] ;
					if( isset( $_POST[ 'drucken' ] ) ) { $drucken = true ; } else { $drucken = false ; }

					if( isset( $_SESSION[ 'dokid' ] ) ) {
						$dokid = $_SESSION[ 'dokid' ] ;
						unset( $_SESSION[ 'dokid' ] ) ;
						if( $weg != '' AND $shortname != '' ) {
							$abfrage = "UPDATE $db_dokumente 
													SET Bezeichnung = '$bezeichng', Beschreibung = '$beschreibung', Drucken = '$drucken', DokumentenPfad = '$weg', Dokument = '$shortname' 
													WHERE DokID = '$dokid'" ;
							}
						else {
							$abfrage = "UPDATE $db_dokumente 
													SET Bezeichnung = '$bezeichng', Beschreibung = '$beschreibung', Drucken = '$drucken'
													WHERE DokID = '$dokid'" ;
							}
						$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						}
					else {
						$abf = "SELECT MAX( Reihenfolge ) AS MaxNr FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundriss" ;
						$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
						$rw = mysql_fetch_object( $erg ) ;
						$next_doc = 1 + $rw->MaxNr ;
						$abfrage	= "INSERT INTO $db_dokumente (`DokID`, `GutachtenID`, `Bezeichnung`, `Beschreibung`, `Drucken`, `Grundriss`, `DokumentenPfad`, `Dokument`, `Reihenfolge` ) 
												VALUES( NULL, '$gutachtenid', '$bezeichng', '$beschreibung', '$drucken', true, '$weg', '$shortname', '$next_doc' )" ;
						$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						}
					$errortxt = $bereich . 'gespeichert' ;
					$bezeichng		= '' ;
					$pfad					= '' ;
					$chg = false ;
				}  // for( $i=0e
			}  // if( count


		unset( $_SESSION[ 'submit' ] ) ;
		}  // if( submit


	elseif( isset( $_POST[ 'back' ] ) ) {
		$chg = false ;
		}
	elseif( isset( $_POST[ 'detail' ] ) ) {
		$dokid = $_POST[ 'detail' ] ;
		unset( $_SESSION[ 'detail' ] ) ;
		unset( $_SESSION[ 'dokid' ] ) ;
		$_SESSION[ 'dokid' ] = $dokid ;
		$chg = true ;
		$abfrage = "SELECT * FROM $db_dokumente WHERE DokID = '$dokid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			include( '../php/get_db_dokumente.php' ) ;
			$route 		= $pfad ;
			}
		}
	elseif( isset( $_POST[ 'delete' ] ) ) {
		$dokid = $_POST[ 'delete' ] ;
		unset( $_POST[ 'delete' ] ) ;
		$abf = "SELECT DokumentenPfad, Dokument FROM $db_dokumente WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $erg ) ) {
			unlink( $pfad ) ;
			}
		$abf = "DELETE FROM $db_dokumente WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $bereich . 'gel&ouml;scht' ;
		$bezeichng		= '' ;
		$pfad					= '' ;
		}
	elseif( isset( $_POST[ 'roof' ] ) ) {
		$dokid = $_POST[ 'roof' ] ;
		unset( $_POST[ 'roof' ] ) ;
		$abf = "SELECT Reihenfolge FROM $db_dokumente WHERE DokID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merknum = $rw->Reihenfolge ;
		$num = 2 ;
		$abf1 = "SELECT DokID FROM $db_dokumente WHERE Grundriss ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$doknr = $rw1->DokID ;
			$num++ ;
			$abf2 = "UPDATE $db_dokumente SET Reihenfolge = '$num' WHERE DokID = $doknr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$abf2 = "UPDATE $db_dokumente SET Reihenfolge = '1' WHERE DokID = $dokid" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		unset( $_POST[ 'rauf' ] ) ;
		$abf = "SELECT Reihenfolge FROM $db_dokumente WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_dokumente SET Reihenfolge = '$old_num' WHERE GutachtenID = '$gutachtenid' AND Grundriss AND Reihenfolge = '$new_num'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_dokumente SET Reihenfolge = '$new_num' WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		unset( $_POST[ 'runter' ] ) ;
		$abf = "SELECT Reihenfolge FROM $db_dokumente WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_dokumente SET Reihenfolge = '$old_num' WHERE GutachtenID = '$gutachtenid' AND Grundriss AND Reihenfolge = '$new_num'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_dokumente SET Reihenfolge = '$new_num' WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'floor' ] ) ) {
		$dokid = $_POST[ 'floor' ] ;
		unset( $_POST[ 'floor' ] ) ;
		$abf = "SELECT DokID, Reihenfolge FROM $db_dokumente WHERE DokID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$merkdok		= $rw->DokID ;
		$merkfolge	= $rw->Reihenfolge ;
		$abf1 = "SELECT DokID, Reihenfolge FROM $db_dokumente WHERE GutachtenID = '$gutachtenid' AND Grundriss AND Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
		$erg1 = mysql_query( $abf1 )  OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $rw1 = mysql_fetch_object( $erg1 ) ) {
			$docnr = $rw1->DokID ;
			$num 	= $rw1->Reihenfolge - 1 ;
			$abf2 = "UPDATE $db_dokumente SET Reihenfolge = '$num' WHERE DokID=$docnr" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			}
		$num 	= $num + 1 ;
		$abf2 = "UPDATE $db_dokumente SET Reihenfolge = '$num' WHERE DokID=$merkdok" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		}
	else {
		}

//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------

	$num = 0 ;
	$abfrag1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundriss ORDER BY Reihenfolge ASC" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebn1 ) ) {
		$num++ ;
		$docid		= $row->DokID ;
		$bezeich	= $row->Bezeichnung ;
		$beschrb	= $row->Beschreibung ;
		$drck 		= $row->Drucken ;
		$weg			= $row->DokumentenPfad . $row->Dokument ;
		$reihe		= $num ;
		$docs [ ] = array( 'docid'=>$docid, 'bezeich'=>$bezeich, 'beschrb'=>$beschrb, 'drck'=>$drck, 'weg'=>$weg, 'reihe'=>$reihe ) ;
		$abfrag2 = "UPDATE $db_dokumente SET Reihenfolge = '$num' WHERE DokID =$docid" ;
		$ergebn2 = mysql_query( $abfrag2 )  OR die( "Error: $abfrag2 <br>". mysql_error() ) ;
		}

//--------------------------------------------------------------------------------------

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;
	include( "../php/head.php" ) ; 
	?>
	<div class="clear"></div>
	<div id="mainb6">

		<form action="" enctype="multipart/form-data" method="post">
			<?php
			if( !$abgeschl ) {
				?>
				<div id="mainb6_oben">
					<div class="trin">
						<label for="bezeichng" class="col1">Bezeichnung</label>
						<input type="text" name="bezeichng" maxlength="150" class="col2a" value="<?php echo $bezeichng ; ?>">
						<label for="beschreibung" class="col1">Beschreibung</label>
						<textarea name="beschreibung" maxlength="64000" cols="100" rows="4" class="col2c" ><?php echo $beschreibung ; ?></textarea>
						<label for="drucken" class="col1b">Drucken</label>
						<input type=checkbox class="col2b" <?php if( $drucken == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="drucken" />
						<div class="col_button">
							<input id='upload' name="upload[]" type="file" multiple="multiple" />
							<div class="clear"></div>
							<div class="col_buttons">
								<button type="submit" Name="submit" class="okay_buttn"></button>
								<?php
								if( $chg ) {
									?>
									<button type="submit" Name="back" class="back_buttn"></button>
									<?php
									}
									?>
								</div>  <!-- buttons -->
							</div>  <!-- button -->
						</div>  <!-- trin -->
					</div>  <!-- maind_oben -->
				<?php
				}
				?>

			<div id="mainb6_unten">
				<div class="clear"></div>
				<div class="trhd"></div>
				<div class="clear"></div>

				<?php
				$anz_docs = count( $docs ) ;
				$i = 0 ;
				while( $i < $anz_docs ) {
					$doc			= $docs [ $i ][ 'docid' ] ;
					$bezeich	= $docs [ $i ][ 'bezeich' ] ;
					$beschrb	= $docs [ $i ][ 'beschrb' ] ;
					$drck			= $docs [ $i ][ 'drck' ] ;
					$weg 			= $docs [ $i ][ 'weg' ] ;
					?>
					<section>
						<section_left>
							<a href="<?php echo $weg ; ?>" target="_blank"><img class="col1a" src="<?php echo $weg ; ?>" /></a>
							</section_left>

						<section_right>
							<div class="clear"></div>
							<div class="col2">Bezeichnung</div>
							<div class="col3"><?php echo $bezeich ; ?></div>
							<div class="clear"></div>
							<div class="col2">Beschreibung</div>
							<div class="col3">
								<textarea disabled maxlength="64000" cols="100" rows="6" class="col2c" ><?php echo $beschrb ; ?></textarea>
								</div>
							<div class="clear"></div>
							<div class="col2">Drucken</div>
							<div class="col3"><input type=checkbox disabled class="td50" <?php if( $drck == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>

							<div class="clear"></div>
							<div class="col_buttons">
								<?php
								if( !$abgeschl ) {
									?>
									<button type="submit" name="detail"	class="edit_buttn" value="<?php echo $doc ; ?>"></button>
									<button type="submit" name="delete"	class="delete_buttn" value="<?php echo $doc ; ?>"></button>
									<?php
									if( $i > 0 ) {
										?>
										<button type="submit" name="roof"	class="oben_buttn" value="<?php echo $doc ; ?>"></button>
										<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $doc ; ?>"></button>
										<?php
										}
									if( $i < $anz_docs - 1 ) {
										?>
										<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $doc ; ?>"></button>
										<button type="submit" name="floor"	class="unten_buttn" value="<?php echo $doc ; ?>"></button>
										<?php
										}
									}
									?>
								</div>  <!-- docbuttn -->
							</section_right>
						</section>

					<?php
					$i++ ;
					}
					?>
				</div>  <!-- maind_unten -->

			</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->