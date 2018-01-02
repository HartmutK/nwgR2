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

	$topic = 'Firmendaten verwalten' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'Stammdaten' ;

	$topic = 'Firmendaten verwalten' ;
	$errortxt = '' ;

	$abfrage = "SELECT * FROM $db_firma WHERE FaID = 1" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_firma.php' ) ;
		}

	if( isset( $_POST[ '1sub' ] ) ) {
		include( '../php/get_input_firma.php' ) ;
		$abfrage = "UPDATE $db_firma SET Bezeichnung = '$fabez', Str = '$fastr', PLZ= '$faplz',
									Ort = '$faort', Tel = '$fatel', Fax = '$fafax', eMail = '$famail', WWW = '$fawww', FN = '$firmennr'
									WHERE FaID = 1" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		}
?>

<?php include( "../php/head.php" ) ; ?>

	<div id="mainstammd">

		<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
			<input type="hidden" Name="sdseite" value='<?php echo $sdseite ; ?>' />

			<div id="buttnbox">
				<button type="submit" name="1sub" class="okay_buttn"></button>
				<a href="../index.php?seite=admin" class="back_buttn"></a>
				</div>

			<label for="fabez" class="input_label_a">Bezeichnung</label>
			<input type="text" name="fabez" maxlength="50" class="input_item_a300" value="<?php echo $fabez ; ?>">
			<label for="fastr" class="input_label_a">Strasse</label>
			<input type="text" name="fastr" maxlength="50" class="input_item_a300" value="<?php echo $fastr ; ?>">
			<label for="faplz" class="input_label_a">PLZ / Ort</label>
			<input type="text" name="faplz" size="5" class="input_item_a50l" value="<?php echo $faplz ; ?>">
			<input type="text" name="faort" maxlength="43" class="input_item_a225" value="<?php echo $faort ; ?>">
			<label for="fatel" class="input_label_a">Telefon</label>
			<input type="tel" name="fatel" class="input_item_a300" value="<?php echo $fatel ; ?>">
			<label for="famail" class="input_label_a">E-Mail</label>
			<input type="email" name="famail" class="input_item_a300" value="<?php echo $famail ?>">
			<label for="fawww " class="input_label_a">www</label>
			<input type="text" name="fawww" class="input_item_a300" value="<?php echo $fawww ; ?>">
			<label for="firmennr" class="input_label_a">Firmenbuch</label>
			<input type="text" name="firmennr" class="input_item_a300" value="<?php echo $firmennr ; ?>">
			<label for="fafax" class="input_label_a">UID</label>
			<input type="text" name="fafax" class="input_item_a300" value="<?php echo $fafax ; ?>">

		</form>
		</div><!-- main -->
	</div>  <!-- container -->
</body>
</html>
<?php
} // if ok
?>
<!-- EOF -->