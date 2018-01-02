<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;

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
	$_SESSION[ 'navi' ] = 'C.5' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	unset( $_SESSION[ 'whngliste' ] ) ;
	$_SESSION[ 'whngliste' ] = array() ;
	$whngliste = array() ;

	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array() ;

	if( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}

	if( !isset( $_SESSION[ 'liste_zub' ] ) ) {
		$_SESSION[ 'liste_zub' ] = array() ;
		$liste_zub = array() ;
		$frage2 = "SELECT * FROM $db_zubehoer ORDER BY Zubehoer";
		$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb2 ) ) {
			$zuxxid	= $row->ZubehoerID ;
			$zubbez	= $row->Zubehoer ;
			$zubmin	= $row->Min;
			$zubxxx	= $row->ZuSchlag ;
			$zubmax	= $row->Max ;
			$zubein	= $row->Einheit ;
			$liste_zub [ ] = array( 'zuid'=>$zuxxid, 'zubbez'=>$zubbez, 'min'=>$zubmin, 'wert'=>$zubxxx, 'max'=>$zubmax, 'einh'=>$zubein ) ;
			} // while Zubehör
		$_SESSION[ 'liste_zub' ] = $liste_zub ;
		} // if isset

	if( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$zid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_whngzuxxx WHERE ZuxxxID = $zid" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = 'Zubeh&ouml;r gel&ouml;scht' ;
		}

	if( isset( $_POST[ 'save_zu' ] ) ) {
		$zid = $_POST[ 'save_zu' ] ;
		$frage2 = "SELECT * FROM $db_zubehoer WHERE ZubehoerID = $zid";
		$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb2 ) ) {
			$zuxxid	= $row->ZubehoerID ;
			$zubbez	= $row->Zubehoer ;
			$zubmin	= $row->Min;
			$zubxxx	= $row->ZuSchlag ;
			$zubmax	= $row->Max ;
			$zubein	= $row->Einheit ;
			$unten	= $row->GanzUnten ;
			$liste_zub [ ] = array( 'zuid'=>$zuxxid, 'zubbez'=>$zubbez, 'min'=>$zubmin, 'wert'=>$zubxxx, 'max'=>$zubmax, 'einh'=>$zubein ) ;
			} // while Zubehör

		$wngid = $_POST[ 'auswahl' ] ;
		$zubbez	= $_POST[ 'zubehoer' ] ;
		$flaeche = $_POST[ 'flaeche' ] ;
		$zublage = $_POST[ 'wglage' ] ;
		$rpfleger = $_POST[ 'rpfleger' ] ;

		$frage2 = "SELECT GanzUnten FROM $db_zubehoer WHERE Zubehoer = '$zubbez'";
		$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergeb2 ) ;
		$unten	= $row->GanzUnten ;

		if( isset( $_SESSION[ 'merkid' ] ) ) {
			$ingid = $_SESSION[ 'merkid' ] ;
			$zubwert = $_POST[ 'zubwert' ] ;
			$frage2 = "UPDATE $db_whngzuxxx 
										SET InWohnung = '$wngid', Bezeichnung = '$zubbez', Zuxxx = '$zubwert', Flaeche = '$flaeche', Flaeche = '$flaeche', Lage = '$zublage', 
												Rechtspfleger = '$rpfleger', GanzUnten = '$unten'
										WHERE ZuxxxID = $ingid" ;
			$errortxt = 'Zubeh&ouml;r ge&auml;ndert' ;
			unset( $_SESSION[ 'merkid' ] ) ;
			$savebuttn = false ;
			}
		else {
			$frage2 = "INSERT INTO $db_whngzuxxx(`ZuxxxID`, `InGutachten`, `InWohnung`, `Bezeichnung`, `Min`, `Zuxxx`, `Max`, `Einheit`, `Zubehoer`, `Flaeche`, `Lage`, `Rechtspfleger`, `GanzUnten` ) 
										VALUES( NULL, '$gutachtenid', '$wngid', '$zubbez', '$zubmin', '$zubxxx', '$zubmax', '$zubein', true, '$flaeche', '$zublage', '$rpfleger', '$unten' )" ;
			$errortxt = 'Zubeh&ouml;r gespeichert' ;
			$savebuttn = true ;
			}
		$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
		$flaeche = 0 ;
		$zublage = '' ;
		unset( $_SESSION[ 'merkwhn' ] ) ;
		} // if save_zu

	if( isset( $_POST[ 'detail' ] ) ) {
		$ingid = $_POST[ 'detail' ] ;
		unset( $_SESSION[ 'merkid' ] ) ;
		$frage = "SELECT * FROM $db_whngzuxxx WHERE ZuxxxID = $ingid" ;
		$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb ) ) {
			$zuxxid		= $row->ZuxxxID ;
			$_SESSION[ 'merkid' ] = $zuxxid ;
			$inid			= $row->InWohnung ;
			$auswahl	= $row->InWohnung ;
			$zubehoer	= $row->Bezeichnung ;
			$flaeche 	= $row->Flaeche ;
			$wglage		= $row->Lage ;
			$rpfleger	= $row->Rechtspfleger ;
			$zubwert	= $row->Zuxxx ;
			}
		unset( $_POST[ 'detail' ] ) ;
		$savebuttn = true ;
		}

// Wohnungsdaten holen
	$frage2 = "SELECT * FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb2 ) ) {
		$wohnungid	= $row->WohnungID ;
		$whngbez		= $row->Bezeichnung ;
		$whnglage 	= $row->Lage ;
		$widmung		= $row->Widmung ;
		$whngliste [ ] = array( 'wid'=>$wohnungid, 'wng'=>$whngbez, 'wlage'=>$whnglage, 'widm'=>$widmung  ) ;

		$frage3 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid AND Zubehoer ORDER BY Bezeichnung" ;
		$ergeb3 = mysql_query( $frage3 )  OR die( "Error: $frage3 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb3 ) ) {
			$zuxxid	= $row->ZuxxxID ;
			$zubbez	= $row->Bezeichnung ;
			$zubmin	= $row->Min;
			$zubxxx	= $row->Zuxxx ;
			$zubmax	= $row->Max ;
			$zubein	= $row->Einheit ;
			$flche	= $row->Flaeche ;
			$zublag	= $row->Lage ;
			$rpfleg	= $row->Rechtspfleger ;
			if( $zublag	== '' ) { $zublag	= $whnglage  ; }
			$liste [ ] = array( 'zuid'=>$zuxxid, 'whng'=>$whngbez, 'whnglage'=>$whnglage, 'whngwidm'=>$widmung,
													'zubbez'=>$zubbez, 'min'=>$zubmin, 'wert'=>$zubxxx, 'max'=>$zubmax, 'einh'=>$zubein, 'flache'=>$flche, 'zublag'=>$zublag, 'rpfleg'=>$rpfleg ) ;
			} // while Zuschlag
		} // while Wohnung	

	$_SESSION[ 'whngliste' ] = $whngliste ;
	$_SESSION[ 'liste' ] = $liste ;

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

//--------------------------------------------------------------------------------------

	include( "../php/head.php" ) ; 
	?>

		<div id="mainc5">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >

				<div id="mainc5_oben">
					<div class="trin">
						<?php
						if( !$abgeschl ) {
							?>
		 					<div class="col1">
			 					<?php
								$j = 0 ;
								$listex = $_SESSION[ 'whngliste' ] ;
								$anzwhg = count( $listex ) ;
								?>
								<select name="auswahl" class="col1">
									<?php
									while( $j < $anzwhg ) {
										$wid		= $listex [ $j ][ 'wid' ] ;
										$wng		= $listex [ $j ][ 'wng' ] ;
										$wlage	= $listex [ $j ][ 'wlage' ] ;
										$widm		= $listex [ $j ][ 'widm' ] ;
										$selct	= $wng . ', ' . $wlage. ', ' . $widm ;
										if( $wid == $auswahl ) { 
											echo '<option selected value="' . $wid . '">' . $selct . '</option>' ;
											}
										else { 	echo '<option value="' . $wid . '">' . $selct . '</option>'; }
										$j++ ;
										}
										?>
									</select>
								</div> <!-- ausw -->

		 					<div class="col2">
			 					<?php
								$i = 0 ;
								$liste = $_SESSION[ 'liste_zub' ] ;
								$anz = count( $liste ) ;
								?>
								<select name="zubehoer" class="col2">
									<?php
									while( $i < $anz ) {
										$zubid		= $liste [ $i ][ 'zuid' ] ;
										$zubbez		= $liste [ $i ][ 'zubbez' ] ;
										$min			= $liste [ $i ][ 'min' ] ;
										$wert			= $liste [ $i ][ 'wert' ] ;
										$max			= $liste [ $i ][ 'max' ] ;
										$einh			= $liste [ $i ][ 'einh' ] ;
										if( $einh != '' ) {  $wert = $wert . ' ' . $einh ; }
										if( $zubbez == $zubehoer ) { 
											echo '<option selected value="' . $zubbez . '">' . $zubbez . '</option>' ;
											}
										else { 	echo '<option value="' . $zubbez . '">' . $zubbez . '</option>'; }
										$i++ ;
										}
										?>
									</select>
								</div> <!-- ausw -->

							<div class="col3"><input type="number" class="col4" maxlength="6" name="zubwert" step="0.01" value="<?php echo $zubwert ?>"></div>
							<div class="col4"><input type="number" class="col3" maxlength="6" name="flaeche" step="0.01" value="<?php echo $flaeche ?>"></div>

		 					<div class="col5">
								<?php
								echo '<select name="wglage" size="1" class="col5">'; 
								$abfrage = "SELECT Kuerzel FROM $db_lagen ORDER BY Kuerzel" ;
								$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg ) ) {
									$wlage = $row->Kuerzel ;
									if( $wlage == $wglage ) { 	echo '<option selected value="' . $wlage . '">' . $wlage . '</option>'; }
									else { 	echo '<option value="' . $wlage . '">' . $wlage . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>

		 					<div class="col6">
								<?php
								echo '<select name="rpfleger" size="1" class="col6">'; 
								$abfrage = "SELECT Rechtspflegertext FROM $db_rpfleger ORDER BY Rechtspflegertext" ;
								$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $erg ) ) {
									$wlage = $row->Rechtspflegertext ;
									if( $wlage == $rpfleger ) { 	echo '<option selected value="' . $wlage . '">' . $wlage . '</option>'; }
									else { 	echo '<option value="' . $wlage . '">' . $wlage . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>

							<div class="col_buttons">
								<button type="submit" name="save_zu" class="okay_buttn" value="<?php echo $zubid ; ?>"></button>
								<?php
								if( $savebuttn ) {
									?>
									<button type="submit" name="bck" class="back_buttn"></button>
									<?php
									}
									?>
								<div class="col_buttons"><?php echo $errortxt ; ?></div>
								</div>
							<?php
							}
						?>
					</div> <!-- tr -->

					<div class="clear"></div>
					<div class="trhd">
						<div class="col1">WE-Objekt</div>
						<div class="col2">Zubeh&ouml;r</div>
						<div class="col3">Wert</div>
						<div class="col4">EFL in m&sup2;</div>
						<div class="col5">Lage</div>
						<div class="col6">Bezeichnung/Rechtspfleger</div>
						</div> <!-- trhd -->
					</div> <!-- mainc5_oben -->

				<div class="clear"></div>
				<div id="mainc5_unten">
					<?php
					$i = 0 ;
					$liste = $_SESSION[ 'liste' ] ;
					$anz = count( $liste ) ;

					while( $i < $anz ) {
						$zuid			= $liste [ $i ][ 'zuid' ] ;
						$whng			= $liste [ $i ][ 'whng' ] ;
						$whnglage	= $liste [ $i ][ 'whnglage' ] ;
						$whngwidm	= $liste [ $i ][ 'whngwidm' ] ;
						$zubbez		= $liste [ $i ][ 'zubbez' ] ;
						$zublag		= $liste [ $i ][ 'zublag' ] ;
						$min			= $liste [ $i ][ 'min' ] ;
						$wert			= $liste [ $i ][ 'wert' ] ;
						$max			= $liste [ $i ][ 'max' ] ;
						$einh			= $liste [ $i ][ 'einh' ] ;
						$rpfleg		= $liste [ $i ][ 'rpfleg' ] ;
						if( $rpfleg == '' ) {  $rpfleg = '-' ; }

						$whng 	= $whng . ', ' . $whnglage . ', ' . $whngwidm ;

						$flache		= $liste [ $i ][ 'flache' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col1"><?php echo $whng ; ?></div>
							<div class="col2"><?php echo $zubbez ; ?></div>
							<div class="col3"><?php echo $wert ; ?> je m&sup2;</div>
							<div class="col4"><?php echo $flache ; ?></div>
							<div class="col5"><?php echo $zublag ; ?></div>
							<div class="col6"><?php echo $rpfleg ; ?></div>
							<?php
							if( !$abgeschl ) {
								?>
								<div class="col_buttons">
									<button type="submit" name="detail" class="edit_buttn" value="<?php echo $zuid ?> "></button>
									<button type="submit" name="del" class="delete_buttn" value="<?php echo $zuid ; ?>"></button>
									</div>
								<?php
								}
								?>
							</div>
						<?php
						$i++ ;
						}
						?>
					</div> <!-- mainc5_unten -->
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->