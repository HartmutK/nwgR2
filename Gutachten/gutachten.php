<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;
	include( '../php/get_input_gutachten.php' ) ;

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
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	$seite = $_REQUEST[ 'seite' ] ;

	if( $seite == 'anfang0' OR $seite == 'anfang1' OR $seite == 'anfang2' ) { 
		unset( $_SESSION[ 'abfrage' ] ) ;
		}


	$anmelden			= $_SESSION[ 'anmelden' ] ;
	$bearbeiter		= $anmelden[ 'id' ] ;;
	$abfrage = "SELECT FirmaID FROM $db_user WHERE UserID = '$bearbeiter'" ;
	$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	if( $row = mysql_fetch_object( $ergeb ) ) {
		$faid = $row->FirmaID ;
		}

	if( $faid == 1 ) {
		$und = ' Firma > 0 ' ;
		}
	else {
		$und = ' Firma = ' . $faid . ' ' ;
		}

	unset( $_SESSION[ 'topic' ] ) ;
	unset( $_SESSION[ 'navi' ] ) ;
	switch( $seite ) {
		case "anfang0":
			$und = ' WHERE' . $und ;
			$_SESSION[ 'abfrage' ] = "SELECT * FROM $db_gutachten" . $und . "ORDER BY Bewertungsstichtag DESC" ;
			$_SESSION[ 'navi' ] = '3' ;
			$_SESSION[ 'topic' ] = 'Alle Gutachten' ;
			break ;
		case "anfang1":
			$_SESSION[ 'abfrage' ] = "SELECT * FROM $db_gutachten WHERE !Abgeschlossen AND" . $und . "ORDER BY Bewertungsstichtag DESC" ;
			$_SESSION[ 'navi' ] = '1' ;
			break ;
		case "anfang2":
			$_SESSION[ 'abfrage' ] = "SELECT * FROM $db_gutachten WHERE Abgeschlossen AND" . $und . " ORDER BY Bewertungsstichtag DESC" ;
			$_SESSION[ 'navi' ] = '2' ;
			break ;
		default:
			$_SESSION[ 'navi' ] = '1' ;
			break ;
		}  // switch $seite

	if( $seite == 'anfang0' OR $seite == 'anfang1' OR $seite == 'anfang2' ) { 
		$seite = 'anfang' ; 
		}
	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = $_SESSION[ 'abfrage' ] ;
	$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		if( $stichtag != '0000-00-00' ) {
			$sticht	= date( "Y.m.d",  strtotime( $stichtag ) ) ;
			}
		else {
			$sticht	= '0000.00.00' ;
			}
		if( $plz == 0 OR $ort == '' OR $str == '' ) {
			$adresse = 'Adresse unvollst&auml;ndig' ;
			}
		else {
			$adresse = $plz . ' ' . $ort . ', ' . $str ;
			}
		$liste [ ] = array( 'tableid'=>$tableid, 'stichtag'=>$sticht, 'gz'=>$gz, 'index'=>$index, 'adresse'=>$adresse, 'abgeschl'=>$abgeschl ) ;
		}

	unset( $_SESSION[ 'gutachtenid' ] ) ;
	$_SESSION[ 'gutachtenid' ] = $tableid ;

	$_SESSION[ 'liste' ] = $liste ;

	switch( $seite ) {
		case "anfang":
			if( isset( $_POST[ '1sub' ] ) ) {  // neu
				$bezeichng	= '' ;
				$abgeschl		= false ;
				$gz					= '' ;
				$index			= '' ;
				$ez					= '' ;
				$gericht		= '' ;
				$stichtag		= '' ;
				$eigner			= '' ;
				$auftrag		= '' ;
				$grundbuch	= '' ;
				$gst_nr			= '' ;
				$plz				= '' ;
				$ort				= '' ;
				$str				= '' ;
				$gesetzl		= '' ;
				$empfehlg		= '' ;
				$baukonsens	= '' ;
				$vertraege	= '' ;
				$entscheide	= '' ;
				$sonstunterl= '' ;
				$nichtbefund	= '' ;
				$praeambel 		= '' ;
				$gutachtn			= '' ;
				$display		= 20 ;
				}
			elseif( isset( $_POST[ '2sub' ] ) ) {  // neu
				$display		= 70 ;
				}
			elseif( isset( $_POST[ 'kopie' ] ) ) {
				$display = 50 ;
				$tableid = $_POST[ 'kopie' ] ;
				}
			elseif( isset( $_POST[ 'drucken' ] ) ) {
				$display = 80 ;
				$tableid = $_POST[ 'drucken' ] ;
				}
			else {
				$display = 10 ;
				}
			break ;
//--------------------------------------------------------------------------------------
		case "new":
			if( isset( $_POST[ '1sub' ] ) ) {
				$display	= 20 ;
				get_input_gutachten( true ) ;
				}
			elseif(  isset( $_POST[ '3sub' ] ) ) {
				$display = 10 ;
				}
			else {
				$display = 20 ;
				}
			break ;
//--------------------------------------------------------------------------------------
		case "kopie":
			if( isset( $_POST[ '3sub' ] ) ) {
				$display = 10 ;
				}
			else {
				$display = 50 ;			
				}
			break ;
//--------------------------------------------------------------------------------------
		case "suche":
			if( isset( $_POST[ 'suchen' ] ) ) {
				$display = 70 ;
				$such_str				= $_POST[ 'such_str' ] ;
				$such_ez				= $_POST[ 'such_ez' ] ;
				$such_auftrag		= $_POST[ 'such_auftrag' ] ;
				$such_grundstk	= $_POST[ 'such_grundstk' ] ;

				if( $such_str != '' ) {
					$bedingung = "Strasse LIKE '%" . $such_str . "%'" ;
					}
				else {
					$bedingung = '' ;
					}

				if( $such_ez != '' ) {
					if( $bedingung != '' ) {
						$bedingung = $bedingung . " OR EZ LIKE '%" . $such_ez . "%'" ;
						}
					else {
						$bedingung = "EZ LIKE '%" . $such_ez . "%'" ;
						}
					}

				if( $such_auftrag != '' ) {
					if( $bedingung != '' ) {
						$bedingung = $bedingung . " OR Auftraggeber LIKE '%" . $such_auftrag . "%'" ;
						}
					else {
						$bedingung = "Auftraggeber LIKE '%" . $such_auftrag . "%'" ;
						}
					}

				if( $such_grundstk != '' ) {
					if( $bedingung != '' ) {
						$bedingung = $bedingung . " OR GST_Nr LIKE '%" . $such_grundstk . "%'" ;
						}
					else {
						$bedingung = "GST_Nr LIKE '%" . $such_grundstk . "%'"  ;
						}
					}

				if( $bedingung != '' ) {
					$_SESSION[ 'abfrage' ] = "SELECT * FROM $db_gutachten WHERE $bedingung ORDER BY Bewertungsstichtag DESC" ;
					$display = 10 ;
					}
				}
			elseif( isset( $_POST[ 'zuruck' ] ) ) {
				$display = 10 ;
				}
			else {
				$display = 70 ;			
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
			$seite	= 'new' ;
			$butt1	= 'Speichern' ;
			$butt2	= '' ;
			break ;
		case 50 :
			$seite	= 'kopie' ;
			$butt1	= 'Kopieren' ;
			$butt2	= '' ;
			break ;
		case 70 :
			$seite	= 'suche' ;
			$butt1	= 'suche' ;
			$butt2	= '' ;
			break ;
		default:
			$display = 10 ;
			$seite	= 'anfang' ;
			$butt1	= 'Neu' ;
			$butt2	= 'Suchen' ;
			break ;
		}  // switch $display
?>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<input type="hidden" Name="seite" value='<?php echo $seite ; ?>' />
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div id="col_buttons">
							<?php
							if( $butt1 != '' ) { 
								if( $display != 10 ) { 
									?> <button type="submit" Name="1sub" class="okay_buttn"></button> <?php 
									}
								else { 
									?> <button type="submit" Name="1sub" class="neu_buttn"></button> <?php
									}
								}
							if( $butt2 != '' ) { ?> <button type="submit" Name="2sub" class="search_buttn"></button> <?php } 
							if( $display != 10 AND $display != 80 ) { ?> <button type="submit" Name="3sub" class="back_buttn"></button> <?php }
							if( $display == 10 ) { ?> <a href="gutachten_nf_import.php?gutachten=0" class="import_buttn"></a> <?php } ?> 
							<div class="errmsg"><?php echo $errortxt ; ?></div>
			 				</div> <!-- buttnbox -->
						</div>
					<div class="clear"></div>
					<div class="trhd">
						<div class="col_80">Stichtag</div>
						<div class="col_160">GZ</div>
						<div class="col_40c">Index</div>
						<div class="col_400">Adresse</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">

					<?php
					if( $display == 70 ) { 
						?>
						<label for="such_str" class="input_label">Stra&szlig;e</label>
						<input type="text" name="such_str" maxlength="50" class="input_item" value="<?php echo $such_str ; ?>">
						<label for="such_ez" class="input_label">EZ</label>
						<input type="text" name="such_ez" maxlength="50" class="input_item" value="<?php echo $such_ez ; ?>">
						<label for="such_auftrag" class="input_label">AuftraggeberInnen</label>
						<input type="text" name="such_auftrag" maxlength="50" class="input_item" value="<?php echo $such_auftrag ; ?>">
						<label for="such_grundstk" class="input_label">Grundst&uuml;cksnummer</label>
						<input type="text" name="such_grundstk" maxlength="50" class="input_item" value="<?php echo $such_grundstk ; ?>">

						<div id="col_buttons">
							<button type="submit" Name="suchen" class="search_buttn"></button>
							<button type="submit" Name="zuruck" class="back_buttn"></button>
			 				</div> <!-- buttnbox -->
						<?php 
						}
					else {
					?> 

				<?php
				}
//----------------------------------------------------------------
				if( $display == 10 ) {

					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz_liste = count( $liste ) ;
					while( $i < $anz_liste ) {
						$tableid		= $liste [ $i ][ 'tableid' ] ;
						$stichtag		= $liste [ $i ][ 'stichtag' ] ;
						$gz					= $liste [ $i ][ 'gz' ] ;
						$index			= $liste [ $i ][ 'index' ] ;
						$adresse		= $liste [ $i ][ 'adresse' ] ;
						$abgeschl		= $liste [ $i ][ 'abgeschl' ] ;
						?>
						<div class="tr">
							<div class="col_80"><?php echo $stichtag ; ?></div>
							<div class="col_160"><?php echo $gz ; ?></div>
							<div class="col_40c"><?php echo $index ; ?></div>
							<div class="col_400"><?php echo $adresse ; ?></div>

							<div class="col_40c">
								<?php
								if( $_SESSION[ 'topic' ] == 'Alle Gutachten' ) {
									if( $abgeschl ) {
										?>
										<div><img src="../CSS/buttons/locked.png"></div>
										<?php
										}
									else {
										?>
										<div><img src="../CSS/buttons/unlocked.png"></div>
										<?php
										}
									}
								?>
								</div>

							<div class="col_175">
								<a href="gutachten_a.php?wasid=<?php echo $tableid ; ?>" class="edit_buttn"></a>
								<a href="copy_proc/cpy_gutachten.php?gutachten=<?php echo $tableid ; ?>" class="copy_buttn"></a>
								<?php
								if( !$abgeschl ) { 
									?>
									<a href="delete_includes/del_gutachten.php?gutachten=<?php echo $tableid ; ?>" class="delete_buttn"></a>
									<a href="gutachten_nf_import.php?gutachten=<?php echo $tableid ; ?>" class="import_buttn"></a>
									<a href="gutachten_nf_export.php?gutachten=<?php echo $tableid ; ?>" class="export_buttn"></a>
									<?php
									}
								else {
									?>
									<a href="gutachten_nf_export.php?gutachten=<?php echo $tableid ; ?>" class="export_buttn1"></a>
									<?php
									}
									?>
								<a href="gutachten_0.php?gutachten=<?php echo $tableid ; ?>" class="print_buttn"></a>
								</div>
							</div>
						<?php
						$i++ ;
						}
					}	// display = 10 //
//----------------------------------------------------------------
				if( $display == 20 ) {  //Neues Gutachten
					?>
					<div class="subheading">A. Allgemeine Angaben</div>
					<input type="date" name="stichtag" class="input_item_a300" value="<?php echo $stichtag ; ?>"> -->
					<label for="gz" class="input_label_a">GZ / Index</label> 
					<input type="text" name="gz" maxlength="50" class="input_item_a240" value="<?php echo $gz ; ?>">

					<?php
					echo '<select name="index" size="1" class="input_item_a50r">'; 
					$abfrage = "SELECT * FROM $db_idx ORDER BY Indx" ;
					$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
					while( $row = mysql_fetch_object( $ergeb ) ) {
						$was = $row->Indx ;
						if( $was == $index ) { echo '<option selected value="' . $was . '">' . $was . '</option>' ; }
						else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
						} //while - array füllen
					echo'</select>';
					?>

					<label for="ez" class="input_label_a">EZ</label>
					<input type="text" name="ez" maxlength="15" class="input_item_a300" value="<?php echo $ez ; ?>">
					<label for="gst_nr" class="input_label_a">Gst-Nr.</label>
					<input type="text" name="gst_nr" maxlength="25" class="input_item_a300" value="<?php echo $gst_nr ; ?>">
					<label for="grundbuch" class="input_label_a">Grundbuch</label>
					<?php
					echo '<select name="grundbuch" size="1" class="input_item_a50l">'; 
					$abfrage = "SELECT * FROM $db_kastral ORDER BY Nummer" ;
					$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
					while( $row = mysql_fetch_object( $ergeb ) ) {
						$was = $row->Nummer ;
						if( $was == $grundbuch) { 
							echo '<option selected value="' . $was . '">' . $was . '</option>' ;
							$gemeinde = $row->Gemeinde ;
							$gericht = $row->Gericht ;
							}
						else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
						} //while - array füllen
					echo'</select>';
					?>
					<input type="text" disabled class="input_item_a230" value="<?php echo $gemeinde ; ?>">

					<label for="gericht" class="input_label_a">Bezirksgericht</label>
					<input type="text" disabled class="input_item_a300" value="<?php echo $gericht ; ?>">
					<label for="plz" class="input_label_a">PLZ / Ort</label>
					<?php
					echo '<select name="plz" size="1" class="input_item_a50l">'; 
					$abfrage = "SELECT * FROM $db_lpo ORDER BY PLZ" ;
					$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
					while( $row = mysql_fetch_object( $ergeb ) ) {
						$was = $row->PLZ ;
						if( $was == $plz ) { 
							echo '<option selected value="' . $was . '">' . $was . '</option>' ;
							$ort = $row->Ort ;
							}
						else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
						} //while - array füllen
					echo'</select>';
					?>
					<input type="text" disabled class="input_item_a230" value="<?php echo $ort; ?>">
					<label for="str" class="input_label_a">Strasse</label>
					<input type="text" name="str" maxlength="50" class="input_item_a300" value="<?php echo $str ; ?>">
					<?php
					}	// display = 20 //
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