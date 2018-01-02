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
	$_SESSION[ 'navi' ] = 'C.7.2' ;

	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

// Gutachtendaten holen
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

// ZuAbschläge holen -> Spaltenüberschriften
	$zuabschlag = array( ) ;

	$abfrage = "SELECT * FROM $db_zuabschlag WHERE Aktiv ORDER BY Kuerzel" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_zuabschlag.php' ) ;
		if( $zuab_kurz == '' ) { $zuab_kurz = '.' ; }

		$c1 = substr( $zuab_kurz, 0, 1 ) ;
		if( $c1 == 'Z' )
			$zuabschlag[ ] = array( 'id'=>$zuabid, 'c1'=>$c1, 'kurz'=>$zuab_kurz, 'menge'=>$zuab_wieviel, 'einheit'=>$einheit, 'fx'=>$fix ) ;
		}
	$anz_zuabschlag = count( $zuabschlag ) ;

// i = Wohnungen
// j = ZuAbschläge
	if( isset( $_POST[ 'save' ] ) ) {
		$wohn = $_SESSION['wohn'] ;
		$anzwohn = count( $wohn ) ;

		$i = 0 ;
		while( $i < $anzwohn ) {
			$wohngid = $wohn [ $i ][ 'wohngid' ] ;

			$rwg = 'RW-' . $wohngid . '-' . $i ;
			if( isset( $_POST[ $rwg ] ) ) {
				$onoff = 1 ;
				}
			else {
				$onoff = 0 ;
				}
			$abfrage = "UPDATE $db_wohnung SET Regelwohnung = $onoff WHERE WohnungID = $wohngid" ;
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;

			$j = 0 ;
			while( $j < $anz_zuabschlag ) {
				$zuab_zuab = $zuabschlag [ $j ][ 'id' ] ;

				$nam = $wohngid . '-' . $j ;
				if( isset( $_POST[ $nam ] ) ) {
					$onoff = 1 ;
					}
				else {
					$onoff = 0 ;
					}
				if( $zuab_zuab <> '' ) {

					$abfrage = "SELECT * FROM $db_zuabwhng WHERE ZuAbWhng = $wohngid AND ZuAb_ZuAb = $zuab_zuab" ;
					$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
					$row = mysql_fetch_object( $ergebnis ) ;
					if(  $row > 0 AND  $onoff == 0 ) {
						$abfrage = "DELETE FROM $db_zuabwhng WHERE ZuAbWhng = $wohngid AND ZuAb_ZuAb = $zuab_zuab" ;
						$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
						}
					if(  $row == 0 AND  $onoff == 1 ) {
						$frage1 = "SELECT * FROM $db_zuabschlag WHERE ZuAbID = $zuab_zuab" ;
						$ergeb1 = mysql_query( $frage1 )  OR die( "Error: $frage1 <br>". mysql_error() ) ;
						$row = mysql_fetch_object( $ergeb1 ) ;
						include( "../php/get_db_zuabschlag.php" ) ;
						$frage2	= "INSERT INTO $db_zuabwhng (`ZuAbWhngID`, `Gut8en`, `ZuAbWhng`, `ZuAb_ZuAb`, `Gruppe`, `ZuAbKurz`, `ZuAbschlag`, `Min`, `Dflt`, `Max`, `Einheit` ) 
													VALUES( NULL, '$tableid', '$wohngid', '$zuab_zuab', '$zuab_gruppe', '$zuab_kurz', '$zuab_komment', '$zuab_min', '$zuab_wieviel', '$zuab_max', '$einheit' )" ;
						$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
						}
					}
				$j++ ;
				}
			$i++ ;
			}
		}

// Wohnungen holen
	$wohn = array( ) ;
	$frage2 = "SELECT * FROM $db_wohnung WHERE InGutachten = $tableid ORDER BY Reihenfolge" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $rw = mysql_fetch_object( $ergeb2 ) ) {
		$wohngid = $rw->WohnungID ;
		$wohnung = $rw->Bezeichnung ;
		$whglage = $rw->Lage ;
		if( $whglage != '' ) { $wohnung = $wohnung . ', ' . $whglage ; }
		$regelwg = $rw->Regelwohnung ;
		$wohn [ ] = array( 'wohngid'=>$wohngid, 'wohnung'=>$wohnung, 'regelwg'=>$regelwg ) ;
		}
	$anzwohn = count( $wohn ) ;
	unset( $_SESSION['wohn'] ) ;
	$_SESSION['wohn'] = $wohn ;

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

// ----------------------------------------------------------------------------------
	include( "../php/head.php" ) ;
	?>
		<div class="clear"></div>

		<div id="mainc7">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainc7_oben">
					<div class="tr">
						<?php
						if( !$abgeschl ) {
							?>
							<div class="col4"><button type="submit" name="save" class="okay_buttn"></button></div>
							<div class="col4"><a onclick="window.open( 'Subroutines/info.php', 'popup', 'width=770, height=800' )"><?php echo '<img src="../Bilder/info.png">' ; ?></a></div>
							<?php
							}
							?>
						<div class="errmsg"><?php echo $errortxt ; ?></div>
						</div> <!-- tr -->

					<div class="trhd">
						<?php
						$i = 0 ;
						$anz_zuabschlag = count( $zuabschlag ) ;
						while( $i < $anz_zuabschlag ) {
							$id			= $zuabschlag [ $i ][ 'id' ] ;
							$c1			= $zuabschlag [ $i ][ 'c1' ] ;
							$kurz		= $zuabschlag [ $i ][ 'kurz' ] ;
							$menge	= $zuabschlag [ $i ][ 'menge' ] . ' ' . $zuabschlag [ $i ][ 'einheit' ] ;
							$fx			= $zuabschlag [ $i ][ 'fx' ] ;
							if( !$fx ) {
								?>
								<div class="col4">
									<a onclick="window.open( 'Subroutines/chg_zuab.php?was=<?php echo $id ; ?>', 'popup', 'width=500, height=200' )"><?php echo $kurz ; ?></a></div>
								<?php
								}
							else {
								?>
								<div class="col4"><?php echo $kurz ; ?></div>
								<?php
								}
							$i++ ;
							}
							?>
						</div> <!-- trhd -->
					</div> <!-- mainc7_oben -->

				<div class="clear"></div>
				<div id="mainc7_unten">
					<?php

// i = ZuAbschläge, ALLE
// j = Wohnungen
					$j = 0 ;
					while( $j < $anzwohn ) {
						$wohngid = $wohn [ $j ][ 'wohngid' ] ;
						$regelwg = $wohn [ $j ][ 'regelwg' ] ;
						$wohnung = $wohn [ $j ][ 'wohnung' ] ;

						$rwg = 'RW-' . $wohngid . '-' . $j ;
						if( ( $j % 2 ) == 0 ) {
							?>
							<div class="tr">
							<?php
							}
						else {
							?>
							<div class="tr_next">
							<?php
							}
							?>


							<div class="tr1">
								<div class="rw1"><?php echo $wohnung ; ?></div>
								<div class="rw2" ><input type="checkbox" <?php if( $regelwg == 1 ) { echo 'checked' ; } ?> name="<?php echo $rwg ; ?>" /></div>
								<div class="rw3">RWhng</div>
								<?php
								$anz_zuabschlag = count( $zuabschlag ) ;
								$merkgebaude = $gebaude ;

								$i = 0 ;
								while( $i < $anz_zuabschlag ) {
									$id = $zuabschlag [ $i ][ 'id' ] ;
									$c1			= $zuabschlag [ $i ][ 'c1' ] ;

									$abfrage = "SELECT * FROM $db_zuabwhng WHERE ZuAbWhng = $wohngid AND ZuAb_ZuAb = $id" ;
									$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
									$row = mysql_fetch_object( $ergebnis ) ;
									if( $row > 0 ) {
										$onoff = 1 ;
										}
									else {
										$onoff = 0 ;
										}
									$nam = $wohngid . '-' . $i ;
									?>
									<div class="col4a"><input type="checkbox" <?php if( $onoff == 1 ) { echo 'checked' ; } ?> name="<?php echo $nam ; ?>" /></div>
									<?php
									$i++ ;
									}
								?>
								</div> <!-- tr1 -->


							</div> <!-- tr -->
							<?php



						$j++ ;
						}
						?>
					</div> <!-- mainc7_unten -->

				</form>
			</div><!-- mainc7 -->
		</div>  <!-- container -->
	</body>
</html>
<?php
	} // if ok
?>
<!-- EOF -->