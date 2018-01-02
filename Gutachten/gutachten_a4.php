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
	$_SESSION[ 'navi' ] = 'A.4' ;

	if( isset( $_POST[ '1sub' ] ) ) {	
		$stichtag		= $_POST[ 'stichtag' ] ;

		$tableid = $_SESSION[ 'gutachtenid' ] ;
		$abfrage = "UPDATE $db_gutachten SET Bewertungsstichtag = '$stichtag' WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Gutachtendaten ge&auml;ndert' ;
		}

	$tableid 	= $_REQUEST[ 'wasid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		unset( $_SESSION[ 'gutachtenid' ] ) ;
		$_SESSION[ 'gutachtenid' ] = $tableid ;
//		$stichtag	= date( "Y.m.d", strtotime( $stichtag ) ) ;
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
					</div>
				<div class="b5tabrow">
					<div class="col_60mr">Wien, am:</div>
					<div class="col_250mr"><input type="date" name="stichtag" class="col_a190_datum" value="<?php echo $stichtag ; ?>">
					</div>
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