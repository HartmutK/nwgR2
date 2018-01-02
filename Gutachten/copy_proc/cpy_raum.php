<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;
	include( 'insert_raum.php' ) ;

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

	$raum = $_REQUEST[ 'raum' ] ;

	$abfrage = "SELECT * FROM $db_raum WHERE RaumID = $raum" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_raeume.php' ) ;
		$bez = $row->Raumart ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$raumart = $_POST[ 'rmart' ] ;
		insert_raum( $raum, $inwohnungid, $raumart ) ;
		$errortxt = 'Raum kopiert' ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../../php/head.php" ) ; ?>

		<div id="mainstammd">
			<div class="tabrowhead">Raum kopieren</div>

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<label for="bez" class="input_label">Bezeichnung - ALT</label>
				<input type="text" disabled name="bez" size="14" class="col_190" value="<?php echo $bez ; ?>">

				<label for="raumart" class="input_label">Bezeichnung - NEU</label>
				<?php
				echo '<select name="rmart" size="1" class="col_190">'; 
				$abfrage = "SELECT Raumart FROM $db_raumart" ;
				$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb ) ) {
					$rmart = $row->Raumart ;
					if( $rmart == $raumart ) { 	echo '<option selected value="' . $rmart . '">' . $rmart . '</option>'; }
					else { 	echo '<option value="' . $rmart . '">' . $rmart . '</option>'; }
					} //while - array füllen
				echo'</select>';
				?>

				<div id="buttnbox">
					<button type="submit" name="save" class="okay_buttn"></button>
					<a href="../gutachten_b4.php" class="back_buttn"></a>
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