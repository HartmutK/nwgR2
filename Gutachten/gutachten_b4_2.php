<?php
	session_start( ) ;
	include( '../php/DBselect.php' ) ;
	include( '../php/Abmeld.php' ) ;
	include( 'Subroutines/berechne_flaeche.php' ) ;

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
	$_SESSION[ 'navi' ] = 'B.4.2' ;

	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

// Gutachtendaten holen
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

// Raumdaten holen
	$raumid = $_REQUEST[ 'wasid' ] ;
	unset( $_SESSION[ 'raumid' ] ) ;
	$_SESSION[ 'raumid' ] = $raumid ;
	$abfrage = "SELECT * FROM $db_raum WHERE RaumID = $raumid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_raeume.php' ) ;
		}

// Wohnungsdaten holen
	$abfrage = "SELECT * FROM $db_wohnung WHERE WohnungID = $inwohnungid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_wohnung.php' ) ;
		if( $nebenraeume ) {
			$widmung = $widmung . ', Nebenr&auml;ume' ;
			}
		}


	if(  isset( $_POST[ 'detail' ] ) ) {
		$massid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_abmessung WHERE MassID = $massid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			include( '../php/get_db_abmessungen.php' ) ;
			$_SESSION[ 'merkid' ] = $row->MassID ;
			$room_a	= $raum_a ;
			$room_b	= $raum_b ;
			$room_c	= $raum_c ;
			$room_t	= $raum_t ;
			$frm		= $form ;
			}
		$_SESSION[ 'massid' ] = $massid ;
		$savebuttn = true ;
		}

	if( isset( $_POST[ 'neu' ] ) ) {
		$room_a	= 0 ;
		$room_b	= 0 ;
		$room_c	= 0 ;
		$room_t	= 0 ;
		$frm		= '' ;
		$savebuttn = true ;
		}

	if( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}

	if( isset( $_POST[ 'delete' ] ) ) {
		$massid = $_POST[ 'delete' ] ;
		$flaeche = 0 ;
		$abfrage = "SELECT * FROM $db_abmessung WHERE MassID = $massid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			include( '../php/get_db_abmessungen.php' ) ;
			$flaeche = $flaeche + berechne_flaeche( $raum_a, $raum_b, $raum_c, $raum_t, $rechne, $addieren ) ;
			$abf = "DELETE FROM $db_abmessung WHERE MassID = $massid" ;
			$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			$abfrage = "SELECT InWohnungID, Flaeche FROM $db_raum WHERE RaumID = $vonraumid" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			while( $row = mysql_fetch_object( $ergebnis ) ) {
				$inwohungid	= $row->InWohnungID ;
				$gesfl			= $row->Flaeche ;
				$gesfl			= $gesfl - $flaeche ;
				$frage = "UPDATE $db_raum SET Flaeche = '$gesfl' WHERE RaumID = $vonraumid" ;
				$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
				$abfrage = "SELECT InGebaeude, Nutzflaeche FROM $db_wohnung WHERE WohnungID = $inwohungid" ;
				$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergebnis ) ) {
					$ingebaeude	= $row->InGebaeude ;
					$gesfl			= $row->Nutzflaeche ;
					$gesfl			= $gesfl - $flaeche ;
					$frage = "UPDATE $db_wohnung SET Nutzflaeche = '$gesfl' WHERE WohnungID = $inwohungid" ;
					$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;

					$frage = "SELECT Nutzflaeche FROM $db_gebaeude WHERE GebaeudeID = $ingebaeude" ;
					$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
					while( $row = mysql_fetch_object( $ergeb ) ) {
						$gesfl = $row->Nutzflaeche ;
						$gesfl = $gesfl - $flaeche ;
						$frage = "UPDATE $db_gebaeude SET Nutzflaeche = '$gesfl' WHERE GebaeudeID = $ingebaeude" ;
						$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
						} // while Gebäude
					} // while Wohnung
				} // while Haus
			} // while
		$errortxt = 'Abmessung gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		} // end if delete

	if( isset( $_POST[ 'save' ] ) ) {
		include( '../php/get_input_abmessungen.php' ) ;
		if( $form <> '' ) {
			if( isset( $_SESSION[ 'massid' ] ) ) {
				$massid = $_SESSION[ 'massid' ] ;
				$abfrage = "UPDATE $db_abmessung SET A = '$raum_a', B = '$raum_b', B = '$raum_b', C = '$raum_c', T = '$raum_t', Form = '$form', Addieren = '$addieren', Flaeche = '$flaeche' 
										WHERE MassID = $massid" ;
				unset( $_SESSION['massid'] ) ;
				}
			else {
				$abfrage	= "INSERT INTO $db_abmessung (`MassID`, `vonRaumID`, `A`, `B`, `C`, `T`, `Form`, `Addieren`, `Flaeche` ) 
											VALUES( NULL, '$vonraumid', '$raum_a', '$raum_b', '$raum_c', '$raum_t', '$form', '$addieren', '$flaeche' )" ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;

			$errortxt = 'Abmessung gespeichert' ;
			$savebuttn	= true ;
			$room_a	= 0 ;
			$room_b	= 0 ;
			$room_c	= 0 ;
			$room_t	= 0 ;
			$frm		= '' ;

			$flaeche = 0 ;
			$abfrage = "SELECT * FROM $db_abmessung WHERE vonRaumID = $raumid" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			while( $row = mysql_fetch_object( $ergebnis ) ) {
				include( '../php/get_db_abmessungen.php' ) ;
				$flaeche = $flaeche + berechne_flaeche( $raum_a, $raum_b, $raum_c, $raum_t, $rechne, $addieren ) ;

				$frage = "UPDATE $db_raum SET Flaeche = '$flaeche' WHERE RaumID = $raumid" ;
				$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
				$abfrage = "SELECT InWohnungID FROM $db_raum WHERE RaumID = $raumid" ;
				$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergebnis ) ) {
					$inwohungid	= $row->InWohnungID ;

					$frage = "SELECT sum( Flaeche ) AS Gesamtflaeche FROM $db_raum WHERE InWohnungID = $inwohungid" ;
					$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
					while( $row = mysql_fetch_object( $ergeb ) ) {
						$gesfl = $row->Gesamtflaeche ;
						$frage = "UPDATE $db_wohnung SET Nutzflaeche = '$gesfl' WHERE WohnungID = $inwohungid" ;
						$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
						$abfrage = "SELECT InGebaeude FROM $db_wohnung WHERE WohnungID = $inwohungid" ;
						$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
						while( $row = mysql_fetch_object( $ergebnis ) ) {
							$ingebaeude	= $row->InGebaeude ;
							$frage = "SELECT sum( Nutzflaeche ) AS Gesamtflaeche FROM $db_wohnung WHERE InGutachten = $gutachtenid" ;
							$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
							while( $row = mysql_fetch_object( $ergeb ) ) {
								$gesfl = $row->Gesamtflaeche ;
								$frage = "UPDATE $db_gutachten SET GesNFL = '$gesfl' WHERE GutachtenID = $gutachtenid" ;
								$ergeb = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
								} // while Gebäude
							} // while Wohnung
						} // while Haus
					} // while
				} // while
			}
		else {
			$errortxt = 'Formangabe fehlt' ;
			}
		}

// Abmessungen holen
	unset( $_SESSION[ 'abmessungen' ] ) ;
	$_SESSION[ 'abmessungen' ] = array() ;
	$abmessungen = array( ) ;
	$abfrage = "SELECT * FROM $db_abmessung WHERE vonRaumID = $raumid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_abmessungen.php' ) ;
		$abmessungen [ ] = array( 'massid'=>$massid, 'vonraumid'=>$vonraumid, 'raum_a'=>$raum_a, 'raum_b'=>$raum_b, 'raum_c'=>$raum_c, 'raum_t'=>$raum_t, 
															'form'=>$form, 'add'=>$addieren, 'formid'=>$formid, 'formel'=>$formel, 'rechne'=>$rechne ) ;
		}
	$_SESSION[ 'abmessungen' ] = $abmessungen ;

	unset( $_POST[ 'detail' ] ) ;
	unset( $_POST[ 'delete' ] ) ;
	unset( $_POST[ 'save' ] ) ;

	$roomdesc = $raumart ;
	if( $whnglage <> $whnglage ) { $roomdesc = $roomdesc . ', ' . $raumlage ; }

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= 'Objekt:' ;
	$head2txt	= $gebbezeich . ', ' . $gebaeudeart ;
	$head3		= 'WE-Objekt:' ;
	$head3txt = $whngbezeichnung . ', ' . $widmung . ', ' . $whnglage ;
	$head4		= 'Raum:' ;
	$head4txt = $roomdesc ;

	include( "../php/head.php" ) ;
	?>

		<div id="main">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div class="b5tabrow">
					<?php
					if( !$abgeschl ) {
						?>
						<div class="col_60mr"><input type="number" name="room_a" class="col_60_r" step="0.01" value="<?php echo $room_a ; ?>"></div>
						<div class="col_60mr"><input type="number" name="room_b" class="col_60_r" step="0.01" value="<?php echo $room_b ; ?>"></div>
						<div class="col_60mr"><input type="number" name="room_c" class="col_60_r" step="0.01" value="<?php echo $room_c ; ?>"></div>
						<div class="col_60mr"><input type="number" name="room_t" class="col_60_r" step="0.01" value="<?php echo $room_t ; ?>"></div>
						<div class="col_180mr">
							<?php
							echo '<select name="frm" size="1" class="col_180">'; 
							$abfrge = "SELECT Form FROM $db_form WHERE Aktiv" ;
							$ergb = mysql_query( $abfrge ) OR die( "Error: $abfrge <br>". mysql_error() ) ;
							while( $row = mysql_fetch_object( $ergb ) ) {
								$frm = $row->Form ;
								if( $frm == $form ) { 	echo '<option selected value="' . $frm . '">' . $frm . '</option>'; }
								else { 	echo '<option value="' . $frm . '">' . $frm . '</option>'; }
								} //while - array füllen
							echo'</select>';
							?>
							</div>
						<div class="col_40_r">Add.</div>
						<div class="col_40"><input type=checkbox class="col_40" <?php echo 'checked' ; ?> name="add" /></div>
						<div class="col_buttons">
							<button type="submit" name="save" class="okay_buttn"></button>
							<?php
							if( $savebuttn ) {
								?>
								<button type="submit" name="bck" class="back_buttn"></button>
								<?php
								}
							else {
								?>
								<a href="gutachten_b4.php" class="back_buttn"></a>
								<?php
								}
								?>
							<div class="col_buttons"><?php echo $errortxt ; ?></div>
							</div>
						<?php
					}
					?>
					</div>





				<div class="b5tabrowhd">
					<div class="col_60_r">a</div>
					<div class="col_60mr_r">b</div>
					<div class="col_60mr_r">c</div>
					<div class="col_60mr_r">t</div>
					<div class="col_form">Form</div>
					<div class="col_60mr_r">Fl&auml;che</div>
					</div>

				<?php
				$i = 0 ;
				$abmessungen = $_SESSION[ 'abmessungen' ] ;
				$anz_abmessungen = count( $abmessungen ) ;

				while( $i < $anz_abmessungen ) {
					$massid			= $abmessungen [ $i ][ 'massid' ] ;
					$vonraumid	= $abmessungen [ $i ][ 'vonraumid' ] ;
					$raum_a			= $abmessungen [ $i ][ 'raum_a' ] ;
					$ram_a			= number_format( round( $raum_a, 2 ), 2, ',', '.' ) ;
					$raum_b			= $abmessungen [ $i ][ 'raum_b' ] ;
					$ram_b			= number_format( round( $raum_b, 2 ), 2, ',', '.' ) ;
					$raum_c			= $abmessungen [ $i ][ 'raum_c' ] ;
					$ram_c			= number_format( round( $raum_c, 2 ), 2, ',', '.' ) ;
					$raum_t			= $abmessungen [ $i ][ 'raum_t' ] ;
					$ram_t			= number_format( round( $raum_t, 2 ), 2, ',', '.' ) ;
					$form				= $abmessungen [ $i ][ 'form' ] ;
					$addieren		= $abmessungen [ $i ][ 'add' ] ;
					$formid			= $abmessungen [ $i ][ 'formid' ] ;
					$formel			= $abmessungen [ $i ][ 'formel' ] ;
					$rechne 		= $abmessungen [ $i ][ 'rechne' ] ;

					$flaeche = berechne_flaeche( $raum_a, $raum_b, $raum_c, $raum_t, $rechne, $addieren ) ;

					$gesflaeche = $gesflaeche + $flaeche ;
					if( !$addieren ) { 
						$form = '-' . $form ;
						$formel = '-' . $formel ;
						} 

					$flch = number_format( round( $flaeche, 2 ), 2, ',', '.' ) ;
					?>
					<div class="b5tabrow">
						<div class="col_60_r"><?php echo $ram_a ; ?></div>
						<div class="col_60mr_r"><?php echo $ram_b ; ?></div>
						<div class="col_60mr_r"><?php echo $ram_c ; ?></div>
						<div class="col_60mr_r"><?php echo $ram_t ; ?></div>
						<div class="col_form"><?php echo $form ; ?></div>
						<div class="col_60mr_r"><?php echo $flch ; ?> m&sup2;</div>
						<?php
						if( !$abgeschl ) {
							?>
							<div class="col_buttons">
								<button type="submit" name="detail"	class="edit_buttn" value="<?php echo $massid ; ?>"></button>
								<button type="submit" name="delete"	class="delete_buttn" value="<?php echo $massid ; ?>"></button>
								</div>
							<?php
							}
							?>
						</div>
					<?php
					$i++ ;
					}
					?>
					<div class="b5tabrow_summe">Gesamt: <?php echo  number_format( round( $gesflaeche, 2 ), 2, ',', '.' ) ; ?> m&sup2;</div>
					<?php
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