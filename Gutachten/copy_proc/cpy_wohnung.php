<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;
	include( 'insert_wohnung.php' ) ;

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

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'copy' ;

	$wohng = $_REQUEST[ 'wohnung' ] ;

	$abfrage = "SELECT * FROM $db_wohnung WHERE WohnungID = '$wohng'" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_wohnung.php' ) ;
		$id		= $wohnungid ;
		$bez	= $whngbezeichnung ;
		$lage = $whnglage ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$lge = $_POST[ 'whnglage' ] ;
		$wng = $_POST[ 'wgnr' ] ;
		insert_wohnung( $ingutachten, $id, $lge, $wng ) ;
		$errortxt = 'Wohnung kopiert' ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../../php/head.php" ) ; ?>

		<div id="mainstammd">
			<div class="tabrowhead">Wohnung kopieren</div>

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >

				<div class="b5tabrow">
					<div class="col_60mr">' '</div>
					<div class="col_60mr">Lage</div>
					<div class="col_60mr">Nummer</div>
					</div>

				<div class="b5tabrow">
					<div class="col_60mr">Alt</div>
					<div class="col_60mr"><?php echo $lage ; ?></div>
					<div class="col_60mr"><?php echo $bez ; ?></div>
					</div>

				<div class="b5tabrow">
					<div class="col_60mr">Neu</div>
					<div class="col_60mr">
						<?php	// Lage
						echo '<select name="whnglage" size="1" class="col_60">'; 
						$abfrage = "SELECT Kuerzel FROM $db_lagen ORDER BY Kuerzel" ;
						$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						while( $row = mysql_fetch_object( $erg ) ) {
							$wglage = $row->Kuerzel ;
							if( $wglage == $whnglage ) { 	echo '<option selected value="' . $wglage . '">' . $wglage . '</option>'; }
							else { 	echo '<option value="' . $wglage . '">' . $wglage . '</option>'; }
							} //while - array füllen
						echo'</select>';
						?>
						</div>
					<div class="col_60mr">
						<?php
						echo '<select name="wgnr" size="1" class="input_drop">'; 
						$abfrage = "SELECT Wohnungnr FROM $db_wohnungnr WHERE Aktiv ORDER BY Wohnungnr" ;
						$erg = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
						while( $row = mysql_fetch_object( $erg ) ) {
							$wgnr = $row->Wohnungnr ;
							if( $wgnr == $whngbezeichnung ) { 	echo '<option selected value="' . $wgnr . '">' . $wgnr . '</option>'; }
							else { 	echo '<option value="' . $wgnr . '">' . $wgnr . '</option>'; }
							} //while - array füllen
						echo'</select>';
						?>
						</div>
					</div>

				<div class="b5tabrow">
					<button type="submit" name="save" class="okay_buttn"></button>
					<a href="../gutachten_b3.php" class="back_buttn"></a>
					<div class="errmsg"><?php echo $errortxt ; ?></div>
					</div> <!-- buttnbox -->
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->