<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;

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
	$_SESSION[ 'navi' ] = 'C.1' ;

	$tableid 	= $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT Gutachten, GZ, PLZ, Ort, Strasse FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	$gutachtn	= $row->Gutachten ;
	$gz				= $row->GZ ;
	$plz			= $row->PLZ ;
	$ort			= $row->Ort ;
	$str			= $row->Strasse ;

	if( isset( $_POST[ '1sub' ] ) ) {	
		$newgut8 = $_POST[ 'gutachtn' ] ;
		$abfrage = "UPDATE $db_gutachten SET Gutachten = '$newgut8' WHERE GutachtenID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Gutachtentext gespeichert' ;
		$abfrage  = "SELECT Gutachten FROM $db_gutachten WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$gutachtn	= $row->Gutachten ;
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

				<div class="b5tabrow">
					<?php
					if( !$abgeschl ) {
						?>
						<div class="col_buttons"><button type="submit" Name="1sub" class="okay_buttn"></button></div>
						<div class="errmsg"><?php echo $errortxt ; ?></div>
						<?php
						}
						?>
					</div> <!-- buttnbox -->

				<div class="b5tabrowhd">Gutachtentext</div>

				<div class="b5tabrow">
					<textarea name="gutachtn" maxlength="64000" cols="122" rows="30" class="icol_600" ><?php echo $gutachtn ; ?></textarea>
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