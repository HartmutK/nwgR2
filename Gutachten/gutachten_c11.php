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
	$_SESSION[ 'navi' ] = 'C.11' ;

	$tableid 	= $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		if( $praeambel == '' ) {
			$abfr  = "SELECT txt FROM $db_txt WHERE TxtArt = 'Epilog'" ;
			$erg = mysql_query( $abfr )  OR die( "Error: $abfr <br>". mysql_error() ) ;
			while( $rw = mysql_fetch_object( $erg ) ) {
				$praeambel = $rw->txt ;
				}
			}
		}

	if( isset( $_POST[ '1sub' ] ) ) {	
		$praeambel	= $_POST[ 'praeambel' ] ;

		$abfrage = "UPDATE $db_gutachten SET Praeambel = '$praeambel' WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Epilog gespeichert' ;
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

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >&ldquo;
				<input type="hidden" Name="seite" value='<?php echo $seite ; ?>' />

				<div id="buttnbox">
					<?php
					if( !$abgeschl ) {
						?>
						<button type="submit" Name="1sub" class="save_buttn"></button>
						<?php
						}
						?>
					<div class="errmsg"><?php echo $errortxt ; ?></div>
					</div> <!-- buttnbox -->

				<textarea name="praeambel" maxlength="64000" cols="122" rows="13" class="input_item1" ><?php echo $praeambel ; ?></textarea>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->