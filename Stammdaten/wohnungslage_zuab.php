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
	$_SESSION[ 'navi' ] = 'Stammdat' ;

	$topic = 'Lageabh&auml;ngige Zu-/Abschl&auml;ge' ;
	$wastxt = 'Lageabh&auml;ngiger Zu-/Abschlag' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$lage = $_POST[ 'wlage' ] ;
		$abf = "SELECT LageID FROM $db_lagen WHERE Kuerzel = '$lage'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $ab <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$lageid = $rw->LageID ;
			}

		$zu_ab = $_POST[ 'ab_zu' ] ;
		$abf = "SELECT ZuAbID FROM $db_zuabschlag WHERE Kommentar = '$zu_ab'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $ab <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$zu_ab = $rw->ZuAbID ;
			}

		$abfrage	= "INSERT INTO $db_zuabauto (`AutoID`, `AutoLage`, `AutoZuAb` ) VALUES( NULL, '$lageid', '$zu_ab' )" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		unset( $_POST['save'] ) ;
		$errortxt = $wastxt . ' gespeichert' ;
		$lagid = 0 ;
		$autom = 0 ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_zuabauto WHERE AutoID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $wastxt . ' gel&ouml;scht' ;
		unset( $_POST['del'] ) ;
		$kuerzel = '' ;
		$lage = '' ;
		}

	$list1 = array() ;
	$abfrage = "SELECT * FROM $db_zuabauto ORDER BY AutoLage" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$autid		= $row->AutoID ;
		$autlage	= $row->AutoLage ;
		$abf = "SELECT * FROM $db_lagen WHERE LageID = '$autlage'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $ab <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$lagekurz	= $rw->Kuerzel ;
			}

		$autzuab	= $row->AutoZuAb ;
		$abf = "SELECT * FROM $db_zuabschlag WHERE ZuAbID = '$autzuab' ORDER BY Kuerzel" ;
		$erg = mysql_query( $abf )  OR die( "Error: $ab <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $erg ) ) {
			$zuabkurz	= $rw->Kuerzel ;
			$zuablang	= $rw->Kommentar ;
			}
		$list1 [ ] = array( 'autid'=>$autid, 'autlage'=>$autlage, 'lagekurz'=>$lagekurz, 'zuabkurz'=>$zuabkurz, 'zuablang'=>$zuablang ) ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="trin">
						<div class="col_80">
							<?php
							$abfrage = "SELECT * FROM $db_lagen WHERE Aktiv ORDER BY Kuerzel" ;
							$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
							echo '<select name="wlage" size="1" class="col_60">'; 
							while( $row = mysql_fetch_object( $ergebnis ) ) {
								$lgeid	= $row->LageID ;
								$was		= $row->Kuerzel ;
								$lge		= $row->Lage ;
								if( $was == $wlage ) { 
									echo '<option selected value="' . $was . '">' . $was . '</option>' ; 
									}
								else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
								} //while - array füllen
							echo'</select>';
							?>
							</div>
						<div class="col_420">
							<?php
							$abfxx = "SELECT ZuAbID, Kommentar FROM $db_zuabschlag WHERE Aktiv AND Gruppe = 'Lage' ORDER BY Kuerzel" ;
							$ergxx = mysql_query( $abfxx ) OR die( "Error: $abfxx <br>". mysql_error() ) ;
							echo '<select name="ab_zu" size="1" class="col_400">'; 
							while( $row = mysql_fetch_object( $ergxx ) ) {
								$wahl = $row->Kommentar ;
								$zu_ab = $row->ZuAbID ;
								if( $wahl == $ab_zu ) {
									echo '<option selected value="' . $wahl . '">' . $wahl . '</option>';
									}
								else { 	echo '<option value="' . $wahl . '">' . $wahl . '</option>'; }
								} //while
							echo'</select>';
							?>
							</div>
						<div class="col_buttons">
							<button type="submit" Name="save" class="okay_buttn"></button>
							<a href="../index.php?seite=admin" class="back_buttn"></a>
							</div> <!-- buttons -->
						<div class="etxt"><?php echo $errortxt ; ?></div>
						</div> <!-- b5tabrow -->	
					<div class="clear"></div>
					<div class="trhd">
						<div class="col_80">Lage</div>
						<div class="col_420">Beschreibung</div>
						</div>
					</div> <!-- mains_oben -->
				<div id="mains_unten">
					<?php
					$i = 0 ;
					$anz = count( $list1 ) ;
					while( $i < $anz ) {
						$autid		= $list1 [ $i ][ 'autid' ] ;
						$autlage	= $list1 [ $i ][ 'autlage' ] ;
						$lagekurz	= $list1 [ $i ][ 'lagekurz' ] ;
						$zuabkurz	= $list1 [ $i ][ 'zuabkurz' ] ;
						$zuablang	= $list1 [ $i ][ 'zuablang' ] ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_80"><?php echo $lagekurz; ?></div>
							<div class="col_420"><?php echo $zuablang ; ?></div>
							<div class="col_buttons">
								<button type="submit" name="del" class="delete_buttn" value="<?php echo $autid ; ?>"></button>
								</div>
							</div>
						<?php
						$i++ ;
						}
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