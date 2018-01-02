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
	$_SESSION[ 'navi' ] = 'C.3' ;

	$tableid 	= $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	unset( $_SESSION[ 'raum' ] ) ;
	$_SESSION[ 'raum' ] = array() ;
	$raum = array( ) ;

	unset( $_SESSION[ 'abmess' ] ) ;
	$_SESSION[ 'abmess' ] = array() ;
	$abmess = array( ) ;

	$frage2 = "SELECT * FROM $db_wohnung WHERE InGutachten = $tableid AND Regelwohnung" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb2 ) ) {
		include( '../php/get_db_wohnung.php' ) ;
		if( $whngbeschreibung == '' ) { $whngbeschreibung = 'Keine ' ; }
		$besichtigung = new DateTime( $besichtigung ) ;
		$besichtigung = $besichtigung->format( 'Y.m.d' ) ;

		$frage3 = "SELECT Bezeichnung FROM $db_gebaeude WHERE 	GebaeudeID = $ingebaeude" ;
		$ergeb3 = mysql_query( $frage3 )  OR die( "Error: $frage3 <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergeb3 ) ;
		if( $row ) {
			$gebbezeich	= $row->Bezeichnung ;
			}
		$frage3 = "SELECT * FROM $db_raum WHERE InWohnungID = $wohnungid ORDER BY Bezeichnung" ;
		$ergeb3 = mysql_query( $frage3 )  OR die( "Error: $frage3 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb3 ) ) {
			include( '../php/get_db_raeume.php' ) ;
			$raum [ ] = array( 'rmid'=>$raumid, 'raumbez'=>$raumbezeichnung, 'rmlage'=>$raumlage, 'rmart'=>$raumart, 'flaeche'=>$flaeche, 'nw'=>$nutzwert, 'nwe'=>$nweinzel ) ;
			}
		$_SESSION[ 'raum' ] = $raum ;

		$frage3 = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnungid ORDER BY Zubehoer, Bezeichnung" ;
		$ergeb3 = mysql_query( $frage3 )  OR die( "Error: $frage3 <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb3 ) ) {
			$zubeh	= $row->Zubehoer ;
			$zubbez	= $row->Bezeichnung ;
			$zubxxx	= $row->Zuxxx ;
			$zubein	= $row->Einheit ;
			$flche	= $row->Flaeche ;
			$zublag	= $row->Lage ;
			$rpfleg	= $row->Rechtspfleger ;
			if( $zublag	== '' ) { $zublag	= $whnglage  ; }
			$liste [ ] = array( 'zubeh'=>$zubeh, 'zubbez'=>$zubbez, 'wert'=>$zubxxx, 'einh'=>$zubein, 'flache'=>$flche, 'zublag'=>$zublag, 'rpfleg'=>$rpfleg ) ;
			} // while Zuschlag
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

	include( "../php/head.php" ) ; 
	?>

		<div class="clear"></div>
		<div id="mainc3">

			<div id="mainc3_oben">

				<div class="clear"></div>
				<div class="trin">
					<div class="col1">Objekt:</div>
					<div class="col2"><?php echo $gebbezeich ; ?></div>
					</div>

				<div class="clear"></div>
				<div class="trin">
					<div class="col1">WE-Objekt:</div>
					<div class="col2"><?php echo $whngbezeichnung ; ?></div>
					<div class="col1">Besichtigung:</div>
					<div class="col2"><?php echo $besichtigung ; ?></div>
					<div class="col1">Zustand:</div>
					<div class="col2"><?php echo $whngzustand ; ?></div>
					<div class="col1">Miete:</div>
					<div class="colges"><?php echo number_format( round( $miete , 2 ), 2, ',', '.' ) ; ?> &euro;</div>
					</div>
				<div class="clear"></div>
				<div class="trin">
					<div class="col1">Widmung:</div>
					<div class="col2"><?php echo $widmung ; ?></div>
					<div class="col1">Lage:</div>
					<div class="col2"><?php echo $whnglage ; ?></div>
					</div>

				<div class="clear"></div>
				<div class="trhd">
					<div class="colu1">Raum</div>
					<div class="col1">Lage</div>
					<div class="colges">Fl&auml;che in m&sup2;</div>
					</div>  <!-- zimmerhead -->
				</div>  <!-- mainc3_oben -->

			<div id="mainc3_unten">

				<?php
				$i = 0 ;
				$raum = $_SESSION[ 'raum' ] ;
				$anz = count( $raum ) ;
				$gesflaeche = 0 ;
				while( $i < $anz ) {
					$rmid			= $raum [ $i ][ 'rmid' ] ;
					$raumbez	= $raum [ $i ][ 'raumbez' ] ;
					$rmart		= $raum [ $i ][ 'rmart' ] ;
					$rmlage		= $raum [ $i ][ 'rmlage' ] ;
					$flaech		= $raum [ $i ][ 'flaeche' ] ;
					$nw				= $raum [ $i ][ 'nw' ] ;
					$nwe			= $raum [ $i ][ 'nwe' ] ;

					$abmess = $_SESSION[ 'abmess' ] ;
					$anz_abmess = count( $abmess ) ;
					$gesflaeche = $gesflaeche + $flaech ;

					?>
					<div class="clear"></div>
					<div class="tr">
						<div class="colu1"><?php echo $rmart ; ?></div>
						<div class="col1"><?php echo $rmlage ; ?></div>
						<div class="colges"><?php echo number_format( round( $flaech, 2 ), 2, ',', '.' ); ?></div>
						</div>
					<?php
					$i++ ;
					}
					?>
				<div class="clear"></div>
				<div class="coltotal">Gesamt: <?php echo number_format( round( $gesflaeche, 2 ), 2, ',', '.' ) ; ?></div>

				<?php
				$i = 0 ;
				$anz = count( $liste ) ;
				$merk_zubeh = '' ;
				while( $i < $anz ) {
					$zubeh	= $liste [ $i ][ 'zubeh' ] ;
					$zubbez	= $liste [ $i ][ 'zubbez' ] ;
					$wert		= $liste [ $i ][ 'wert' ] ;
					$einh		= $liste [ $i ][ 'einh' ] ;
					$flache	= $liste [ $i ][ 'flache' ] ;
					$zublag	= $liste [ $i ][ 'zublag' ] ;
					$rpfleg	= $liste [ $i ][ 'rpfleg' ] ;
					if( $rpfleg != '' ) {
						$zubbez = $rpfleg ;
						}
					if( !$zubeh ) {
						$heading = 'Zuschl&auml;ge' ;
						}
					else {
						$heading = 'Zubeh&ouml;r' ;
						}
					if( $merk_zubeh != $zubeh ) {
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="colhead"><?php echo $heading ; ?></div>
							</div>
						<?php
						}
					$merk_zubeh = $zubeh ;
					?>
					<div class="clear"></div>
					<div class="tr">
						<div class="colu1"><?php echo $zubbez ; ?></div>
						<div class="col1"><?php echo $zublag ; ?></div>
						<div class="colges"><?php echo number_format( round( $flache, 2 ), 2, ',', '.' ) ; ?></div>
						</div>
					<?php
					$i++ ;
					}
					?>
				</div>  <!-- mainc3_unten -->

			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->