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
	$_SESSION[ 'navi' ] = 'D' ;

	$bereich = 'Grundbuchrisse' ;
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
					$beschreibung = '' ;
					if( isset( $_POST[ 'seite1' ] ) ) { $seite1 = true ; } else { $seite1 = false ; }
					if( isset( $_POST[ 'titelbild' ] ) ) { $titelbild = true ; } else { $titelbild = false ; }
					if( isset( $_POST[ 'lagepln' ] ) ) { $lagepln = true ; } else { $lagepln = false ; }
					if( isset( $_POST[ 'bild' ] ) ) { $bild = true ; } else { $bild = false ; }
					if( isset( $_POST[ 'drucken' ] ) ) { $drucken = true ; } else { $drucken = false ; }

					if( isset( $_SESSION[ 'dokid' ] ) ) {
						$dokid = $_SESSION[ 'dokid' ] ;
						unset( $_SESSION[ 'dokid' ] ) ;
						if( $weg != '' AND $shortname != '' ) {
							$abfrage = "UPDATE $db_dokumente 
													SET Bezeichnung = '$bezeichng', Drucken = '$drucken', Bild = '$bild', Titelbild = '$titelbild', Seite1Foto = '$seite1', Lageplan = '$lagepln', 
															DokumentenPfad = '$weg', Dokument = '$shortname' 
													WHERE DokID = $dokid" ;
							}
						else {
							$abfrage = "UPDATE $db_dokumente 
													SET Bezeichnung = '$bezeichng', Drucken = '$drucken', Bild = '$bild', Titelbild = '$titelbild', Seite1Foto = '$seite1', Lageplan = '$lagepln'
													WHERE DokID = $dokid" ;
							}
						$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						}
					else {
						$abf = "SELECT MAX( Reihenfolge ) AS MaxNr FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND Grundriss" ;
						$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
						$rw = mysql_fetch_object( $erg ) ;
						$next_doc = 1 + $rw->MaxNr ;
						$abfrage	= "INSERT INTO $db_dokumente (`DokID`, `GutachtenID`, `Bezeichnung`, `Beschreibung`, `Titelbild`, `Seite1Foto`, `Drucken`, `Grundbuch`, `Bild`, `Grundriss`, `Lageplan`, 
																										`DokumentenPfad`, `Dokument`, `Reihenfolge` ) 
												VALUES( NULL, '$gutachtenid', '$bezeichng', '$beschreibung', '$titelbild', '$seite1', '$drucken', false, '$bild', false, '$lagepln', '$weg', '$shortname', '$next_doc' )" ;
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
			$lagepln	= $row->Lageplan ;
			$route 		= $pfad ;
			}
		}
	elseif( isset( $_POST[ 'delete' ] ) ) {
		$dokid = $_POST[ 'delete' ] ;
		unset( $_POST[ 'delete' ] ) ;
		$abf = "SELECT DokumentenPfad, Dokument FROM $db_dokumente WHERE DokID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $erg ) ) {
			$pfad = $row->DokumentenPfad .  $row->Dokument ;
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
		$abf = "UPDATE $db_dokumente SET Reihenfolge = '$old_num' WHERE GutachtenID = '$gutachtenid' AND ( Lageplan OR Bild ) AND Reihenfolge = '$new_num'" ;
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
		$abf = "UPDATE $db_dokumente SET Reihenfolge = '$old_num' WHERE GutachtenID = '$gutachtenid' AND ( Lageplan OR Bild ) AND Reihenfolge = '$new_num'" ;
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
		$abf1 = "SELECT DokID, Reihenfolge FROM $db_dokumente WHERE GutachtenID = '$gutachtenid' AND ( Lageplan OR Bild ) AND Reihenfolge>$merkfolge ORDER BY Reihenfolge" ;
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
	$abfrag1 = "SELECT * FROM $db_dokumente WHERE GutachtenID = $gutachtenid AND ( Lageplan OR Seite1Foto OR Titelbild OR Bild ) ORDER BY Reihenfolge ASC" ;
	$ergebn1 = mysql_query( $abfrag1 )  OR die( "Error: $abfrag1 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebn1 ) ) {
		$num++ ;
		$docid		= $row->DokID ;
		$bezeich	= $row->Bezeichnung ;
		$seit1		= $row->Seite1Foto ;
		$titelb		= $row->Titelbild ;
		$bild			= $row->Bild ;
		$lagepl		= $row->Lageplan ;
		$drck 		= $row->Drucken ;
		$weg			= $row->DokumentenPfad . $row->Dokument ;
		$reihe		= $num ;
		$docs [ ] = array( 'docid'=>$docid, 'bezeich'=>$bezeich, 'seit1'=>$seit1, 'titelb'=>$titelb, 'bild'=>$bild, 'lagepl'=>$lagepl, 'drck'=>$drck, 'weg'=>$weg, 'reihe'=>$reihe ) ;
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
	<div id="maind">

		<form action="" enctype="multipart/form-data" method="post">
			<?php
			if( !$abgeschl ) {
				?>
				<div id="maind_oben">
					<div class="trin">
						<label for="bezeichng" class="col1">Bezeichnung:</label>
						<input type="text" name="bezeichng" maxlength="150" class="col2a" value="<?php echo $bezeichng ; ?>">
						<label for="seite1" class="col1">Deckblatt:</label>
						<input type=checkbox class="col2b" <?php if( $seite1 == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="seite1" />
						<label for="titelbild" class="col1">Geb&auml;ude:</label>
						<input type=checkbox class="col2b" <?php if( $titelbild == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="titelbild" />
						<label for="lagepln" class="col1">Lageplan:</label>
						<input type=checkbox class="col2b" <?php if( $lagepln == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="lagepln" />
						<label for="bild" class="col1">Foto:</label>
						<input type=checkbox class="col2b" <?php if( $bild == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="bild" />
						<label for="drucken" class="col1">Drucken:</label>
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

			<div id="maind_unten">
				<div class="clear"></div>
				<div class="trhd"></div>
				<div class="clear"></div>

				<?php
				$anz_docs = count( $docs ) ;
				$i = 0 ;
				while( $i < $anz_docs ) {
					$doc			= $docs [ $i ][ 'docid' ] ;
					$bezeich	= $docs [ $i ][ 'bezeich' ] ;
					$eins			= $docs [ $i ][ 'seit1' ] ;
					$titelb		= $docs [ $i ][ 'titelb' ] ;
					$bild			= $docs [ $i ][ 'bild' ] ;
					$lagepl		= $docs [ $i ][ 'lagepl' ] ;
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
							<div class="col2">Deckblatt</div>
							<div class="col3"><input type=checkbox disabled class="td50" <?php if( $eins == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>
							<div class="clear"></div>
							<div class="col2">Geb&auml;ude</div>
							<div class="col3"><input type=checkbox disabled class="td50" <?php if( $titelb == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>
							<div class="clear"></div>
							<div class="col2">Lageplan</div>
							<div class="col3"><input type=checkbox disabled class="td50" <?php if( $lagepl == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>
							<div class="clear"></div>
							<div class="col2">Foto</div>
							<div class="col3"><input type=checkbox disabled class="td50" <?php if( $bild == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> /></div>
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