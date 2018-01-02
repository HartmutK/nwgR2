<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;
	include( '../php/get_input_gutachten.php' ) ;

	if( isset( $_SESSION[ 'anmelden' ] ) ) {
		if( !$_SESSION[ 'anmelden' ][ 'angemeldet' ] ) { $ok = false ; }
		else { $ok = true ;	}
		}
	else {
		$ok = false ;
		}

if( $ok ) {
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'A.1' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( isset( $_POST[ 'detail' ] ) ) {
		$merkid = $_POST[ 'detail' ] ;
		unset( $_SESSION[ 'merkid' ] ) ;
		$_SESSION[ 'merkid' ] = $merkid ;
		$abfrage = "SELECT * FROM $db_auftraggeb WHERE ID = '$merkid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$firma1		= $row->Firma1 ;
		$firma2		= $row->Firma2 ;
		$titel_v	= $row->Titel_vorne ;
		$anrede		= $row->Anrede ;
		$name			= $row->Name ;
		$vorname	= $row->Vorname ;
		$titel_h	= $row->Titel_hinten ;
		$position	= $row->Position ;
		$strasse	= $row->Strasse ;
		$land			= $row->Land ;
		$plz			= $row->PLZ ;
		$ort			= $row->Ort ;
		$mail			= $row->eMail ;
		$mobil		= $row->Mobil ;
		$telefon	= $row->Telefon ;
		$www			= $row->WWW ;
		$savebuttn = true ;
		unset( $_POST[ 'detail' ] ) ;
		}
	elseif( isset( $_POST[ 'save' ] ) ) {
		$firma1		= $_POST[ 'firma1' ] ;
		$firma2		= $_POST[ 'firma2' ] ;
		$titel_v	= $_POST[ 'titel_v' ] ;
		$anrede		= $_POST[ 'anrede' ] ;
		$name			= $_POST[ 'name' ] ;
		$vorname	= $_POST[ 'vorname' ] ;
		$titel_h	= $_POST[ 'titel_h' ] ;
		$position	= $_POST[ 'position' ] ;
		$strasse	= $_POST[ 'strasse' ] ;
		$land			= $_POST[ 'land' ] ;
		$plz			= $_POST[ 'plz' ] ;
		$ort			= $_POST[ 'ort' ] ;
		$mail			= $_POST[ 'mail' ] ;
		$mobil		= $_POST[ 'mobil' ] ;
		$telefon	= $_POST[ 'telefon' ] ;
		$www			= $_POST[ 'www' ] ;
		$position	= $_POST[ 'position' ] ;
		$merkid		= $_SESSION[ 'merkid' ] ;
		if( $merkid != 0 ) {
			$abfrage = "UPDATE $db_auftraggeb SET Firma1 = '$firma1', Firma2 = '$firma2', 
										Titel_vorne = '$titel_v', Anrede = '$anrede', Name = '$name', Vorname = '$vorname', Titel_hinten = '$titel_h', Position = '$position',  
										Strasse = '$strasse', Land = '$land', PLZ = '$plz', Ort = '$ort', Telefon = '$telefon', Mobil = '$mobil', eMail = '$mail', WWW = '$www'
										WHERE ID = '$merkid'" ;
			$errortxt = 'Auftraggeber ge&auml;ndert' ;
			unset( $_SESSION[ 'merkid' ] ) ;
			}
		else {
			$abfrage	= "INSERT INTO $db_auftraggeb (`ID`, `Firma1`, `Firma2`, `Titel_vorne`, `Anrede`, `Name`, `Vorname`, `Titel_hinten`, `Position`, 
																`Strasse`, `Land`, `PLZ`, `Ort`, `Telefon`, `Mobil`, `eMail`, `WWW` )
										VALUES( NULL, '$firma1', '$firma2', '$titel_v', '$anrede', '$name', '$vorname', '$titel_h', '$position',
																'$strasse', '$land', '$plz', '$ort', '$telefon', '$mobil', '$mail', '$www' )" ;
			$errortxt = 'Auftraggeber gespeichert' ;
			}
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$firma1		= '' ;
		$firma2		= '' ;
		$titel_v	= '' ;
		$anrede		= '' ;
		$name			= '' ;
		$vorname	= '' ;
		$titel_h	= '' ;
		$position	= '' ;
		$strasse	= '' ;
		$land			= '' ;
		$plz			= '' ;
		$ort			= '' ;
		$telefon	= '' ;
		$mobil		= '' ;
		$mail			= '' ;
		$www			= '' ;
		unset( $_POST[ 'save' ] ) ;
		$savebuttn = false ;
		}
	elseif( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}
	elseif( isset( $_POST[ 'selct' ] ) ) {
		$tableid = $_POST[ 'selct' ] ;
		if( $tableid != $auftrag ) {
			$abf = "UPDATE $db_gutachten SET Auftraggeber = $tableid WHERE GutachtenID = $gutachtenid" ;
			$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			$errortxt = 'Auftraggeber ge&auml;ndert' ;
			}
		unset( $_POST['selct'] ) ;
		}
	elseif( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "UPDATE $db_auftraggeb SET Aktiv = 0 WHERE ID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Auftraggeber deaktiviert' ;
		unset( $_POST['delete'] ) ;
		}
	else {
		}

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

//--------------------------------------------------------------------------------------

	$abfrage = "SELECT * FROM $db_auftraggeb WHERE ID = $auftrag" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	$fma1		= $row->Firma1 ;
	$fma2		= $row->Firma2 ;
	$anr		= $row->Anrede . ' ' . $row->Titel_vorne ;
	$nm			= $row->Name ;
	$vnm		= $row->Vorname ;
	$tit_h	= $row->Titel_hinten ;
	$posit	= $row->Position ;
	$stra		= $row->Strasse ;
	$lnd		= $row->Land ;
	$lpo		= $row->PLZ . ' ' .  $row->Ort ;
	$tlf		= $row->Telefon ;
	$mbl		= $row->Mobil ;
	$ml			= $row->eMail ;
	$weh		= $row->WWW ;
	if( $vnm != '' ) $nm = $vnm . ' ' . $nm ;
	if( $tit_h != '' ) $nm = $nm . ', ' . $tit_h ;
	if( $lnd != '' ) $lpo = $lnd . '-' . $lpo ;
	
	$abfrage = "SELECT * FROM $db_auftraggeb WHERE Aktiv ORDER BY Firma1, Firma2, Name, Vorname" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$eidie				= $row->ID ;
		$fa1		= $row->Firma1 ;
		$fa2		= $row->Firma2 ;
		$titl_v	= $row->Titel_vorne ;
		$anred		= $row->Anrede ;
		$nam			= $row->Name ;
		$vornam	= $row->Vorname ;
		$titl_h	= $row->Titel_hinten ;
		$posit	= $row->Position ;
		$liste [ ] = array( 'eidie'=>$eidie, 'fa1'=>$fa1, 'fa2'=>$fa2, 'anred'=>$anred, 'titl_v'=>$titl_v, 'titl_h'=>$titl_h, 'n'=>$nam, 'vn'=>$vornam, 'pos'=>$posit ) ;
		}

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;
//--------------------------------------------------------------------------------------
?>

<?php include( "../php/head.php" ) ; ?>

			<div class="clear"></div>
			<div id="maina1">
				<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
					<div class="clear"></div>
					<div id="maina1_oben">
						<?php
						if( !$abgeschl ) {
							?>
							<div class="clear"></div>
							<div class="trin">
								<div class="col1">Firma:</div>
								<div class="col2"><input type="text" name="firma1" class="col2" value="<?php echo $firma1 ; ?>"></div>
								<div class="col3">Anrede/Titel:</div>
								<div class="col4a"><input type="text" name="anrede" class="col4a" value="<?php echo $anrede ; ?>"></div>
								<div class="col4b"><input type="text" name="titel_v" class="col4b" value="<?php echo $titel_v ; ?>"></div>
								<div class="col5">Stra&szlig;e:</div>
								<div class="col6"><input type="text" name="strasse" class="col6" value="<?php echo $strasse ; ?>"></div>
								<div class="col7">Telefon:</div>
								<div class="col8"><input type="text" name="telefon" class="col8" value="<?php echo $telefon ; ?>"></div>
								<div class="col_buttons">
									<button type="submit" name="save" class="okay_buttn"></button>
									<?php
									if( $savebuttn ) {
										?>
										<button type="submit" name="bck" class="back_buttn"></button>
										<?php
										}
									?>
									</div>  <!-- buttons -->
								</div>  <!-- trin -->

							<div class="clear"></div>
							<div class="trin">
								<div class="col2a"><input type="text" name="firma2" class="col2" value="<?php echo $firma2 ; ?>"></div>
								<div class="col3">Name:</div>
								<div class="col4"><input type="text" name="name" class="col4" value="<?php echo $name ; ?>"></div>
								<div class="col5">Land/PLZ/Ort:</div>
								<div class="col6a"><input type="text" name="land" class="col6a" value="<?php echo $land ; ?>"></div>
								<div class="col6b"><input type="text" name="plz" class="col6b" value="<?php echo $plz ; ?>"></div>
								<div class="col6c"><input type="text" name="ort" class="col6c" value="<?php echo $ort ; ?>"></div>
								<div class="col7">Mobil:</div>
								<div class="col8"><input type="text" name="mobil" class="col8" value="<?php echo $mobil ; ?>"></div>
								<div class="etxt"><?php echo $errortxt ; ?></div>
							</div>

							<div class="clear"></div>
							<div class="trin">
								<div class="col3a">Vorname:</div>
								<div class="col4"><input type="text" name="vorname" class="col4" value="<?php echo $vorname ; ?>"></div>
								<div class="col5">www:</div>
								<div class="col6"><input type="text" name="www" class="col6" value="<?php echo $www ; ?>"></div>
								<div class="col7">E-Mail:</div>
								<div class="col8"><input type="text" name="mail" class="col8" value="<?php echo $mail ; ?>"></div>
								</div>
								<?php
								}
								?>
						</div>  <!-- maina1_oben -->

					<div class="clear"></div>
					<div id="maina1_middle">
						<div class="clear"></div>
						<div class="trhd">Auftraggeber</div>


						<div class="clear"></div>
						<div id="mitte">
							<div class="trd">
								<div class="colm"><?php echo $fma1; ?></div>
								<div class="colm"><?php echo $anr ; ?></div>
								<div class="colm"><?php echo $stra ; ?></div>
								<div class="colm"><?php echo $tlf ; ?></div>
								</div>
							<div class="clear"></div>
							<div class="trd">
								<div class="colm"><?php echo $fma2 ; ?></div>
								<div class="colm"><?php echo $nm ; ?></div>
								<div class="colm"><?php echo $lpo ; ?></div>
								<div class="colm"><?php echo $mbl ; ?></div>
								</div>
							<div class="clear"></div>
							<div class="trd">
								<div class="colm1"><?php echo $posit ; ?></div>
								<div class="colm"><?php echo $ml ; ?></div>
								<div class="colm"><?php echo $weh ; ?></div>
								</div>
							</div>  <!-- mitte -->
						</div>  <!-- maina1_middle -->

					<div class="clear"></div>
					<div id="maina1_unten">
						<?php
						if( !$abgeschl ) {
							?>
							<div class="clear"></div>
							<div class="trhd1">
								<div class="colm">Firma</div>
								<div class="colm">Mitarbeiter</div>
								</div>
							<div id="mitte">
								<?php
								$i = 0 ;
								$anz = count( $liste ) ;
								while( $i < $anz ) {
									$eidie		= $liste [ $i ][ 'eidie' ] ;
									$fa1			= $liste [ $i ][ 'fa1' ] ;
									$fa2			= $liste [ $i ][ 'fa2' ] ;
									if( $fa2 != '' ) {
										$fa1 = $fa1 . ', ' . $fa2 ;
										}
									$anred		= $liste [ $i ][ 'anred' ] ;
									$titl_v		= $liste [ $i ][ 'titl_v' ] ;
									$vn				= $liste [ $i ][ 'vn' ] ;
									$wer				= $liste [ $i ][ 'n' ] ;
									if( $vn != '' ) $wer = $vn . ' ' . $wer ;
									if( $titl_v != '' ) $wer = $titl_v . ' ' . $wer ;
									if( $anred != '' ) $wer = $anred . ' ' . $wer ;
		
									$titel_h	= $liste [ $i ][ 'titel_h' ] ;
									if( $titl_h != '' ) $wer = $wer . ' , ' . $titl_v ;
	
									$pos			= $liste [ $i ][ 'pos' ] ;
									if( $pos != '' ) $wer = $wer . ' , ' . $pos ;
									?>
									<div class="clear"></div>
									<div class="tr">
										<div class="colm"><?php echo $fa1 ; ?></div>
										<div class="colm"><?php echo $wer ; ?></div>
										<div class="col_buttons">
											<button type="submit" name="detail" class="edit_buttn" value="<?php echo $eidie ; ?>"></button>
											<button type="submit" name="del" class="delete_buttn" value="<?php echo $eidie ; ?>"></button>
											<button type="submit" name="selct" class="step_plus_buttn" value="<?php echo $eidie ; ?>"></button>
											</div>
										</div>
									<?php
									$i++ ;
									}
								?>
								</div>
								<?php
							}
							?>
						</div>  <!-- maina1_unten -->
					</form>
				</div>  <!-- maina1 -->
			</div>  <!-- container -->
		</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->