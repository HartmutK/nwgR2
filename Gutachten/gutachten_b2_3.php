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
	$_SESSION[ 'navi' ] = 'B.2.3' ;

	$tableid 	= $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT Ausstattung, Abgeschlossen FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$ausstattung	= $row->Ausstattung ;
		$abgeschl			= $row->Abgeschlossen ;
		}

	if( isset( $_POST[ '1sub' ] ) ) {	
		$ausstattung = $_POST[ 'ausstattung' ] ;

		$abfrage = "UPDATE $db_gutachten SET Ausstattung = '$ausstattung' WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Ausstattung gespeichert' ;
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

		<div id="mainb2">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >&ldquo;
				<?php
				if( !$abgeschl ) {
					?>
					<div class="col_buttons">
						<button type="submit" Name="1sub" class="okay_buttn"></button>
						<div class="etxt"><?php echo $errortxt ; ?></div>
						</div>
					<?php
					}
					?>
				<div class="clear"></div>
				<div class="zeile">
					<textarea name="ausstattung" maxlength="64000" cols="122" rows="13" class="col2" ><?php echo $ausstattung ; ?></textarea>
					</div>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->