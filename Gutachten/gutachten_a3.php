<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;
	include( '../php/get_input_gutachten.php' ) ;

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
	$_SESSION[ 'navi' ] = 'A.3' ;

	$tableid 	= $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( isset( $_POST[ '1sub' ] ) ) {	
		$zweck			= $_POST[ 'zweck' ] ;
		$zweckgrund	= $_POST[ 'zweckgrund' ] ;

		$abfrage = "UPDATE $db_gutachten SET Zweck = '$zweck', Zweckgrund = '$zweckgrund' WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Gutachtenzweck gespeichert' ;
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

		<div id="main">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<input type="hidden" Name="seite" value='<?php echo $seite ; ?>' />

				<div id="buttnbox">
					<?php
					if( !$abgeschl ) {
						?>
						<button type="submit" Name="1sub" class="okay_buttn"></button>
						<?php
						}
						?>
					<div class="errmsg"><?php echo $errortxt ; ?></div>
					</div> <!-- buttnbox -->

				<label for="zweck" class="input_label_a">Zweck:</label>
				<?php
				echo '<select name="zweck" size="1" class="input_item_a600">'; 
//				echo '<select textarea name="zweck" size="1" maxlength="600" cols="122" rows="5" class="input_item_a600">'; 
				$abfrage = "SELECT * FROM $db_gut8enzweck ORDER BY Zweck" ;
				$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb ) ) {
					$was = $row->Zweck ;
					?>
					<?php 
					if( $was == $zwck) { 
						echo '<option selected value="' . $was . '">' . $was . '</option>' ;
						}
					else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
					} //while - array füllen
				echo'</select>';
				?>

<!--				<textarea name="zweck" maxlength="600" cols="122" rows="5" class="input_item_a600" ><?php echo $zweck ; ?></textarea> -->
				<label for="zweckgrund" class="input_label_a">Begr&uuml;ndung:</label>
				<textarea name="zweckgrund" maxlength="64000" cols="110" rows="13" class="input_item_a600" ><?php echo $zweckgrund ; ?></textarea>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->