<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;
	include( 'insert_gebaude.php' ) ;

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

	$gebde = $_REQUEST[ 'gebaude' ] ;

	$abfrage = "SELECT * FROM $db_gebaeude WHERE GebaeudeID = $gebde" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_gebaeude.php' ) ;
		$bez = $gebbezeich ;
		$bezeichnung = '' ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$bezeichnung = $_POST[ 'bezeichnung' ] ;
		insert_gebaude( $ingutachten, $bezeichnung ) ;
		$errortxt = 'Geb&auml;ude kopiert' ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../../php/head.php" ) ; ?>

		<div id="mainstammd">
			<div class="tabrowhead">Geb&auml;ude kopieren</div>

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<label for="bez" class="input_label">Bezeichnung - ALT</label>
				<input type="text" disabled name="bez" size="25" class="input_item" value="<?php echo $bez ; ?>">

				<label for="bezeichnung" class="input_label">Bezeichnung - NEU</label>
				<input type="text" name="bezeichnung" size="25" class="input_item" value="<?php echo $bezeichnung ; ?>">

				<div class="b5tabrow">
					<button type="submit" name="save" class="okay_buttn"></button>
					<a href="../gutachten_b2_2.php" class="back_buttn"></a>
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