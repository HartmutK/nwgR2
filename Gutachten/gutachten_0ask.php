<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = '1' ;

	$gutachten = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachten" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$gz				= $row->GZ ;
		$plz			= $row->PLZ ;
		$ort			= $row->Ort ;
		$str			= $row->Strasse ;
		$abgeschl	= $row->Abgeschlossen ;
		}

	$head1 = $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;

	if( isset( $_POST[ 'okay' ] ) ) {
		$ausgestellt = date( 'Y-m-d' ) ;
		$abf0 = "UPDATE $db_gutachten SET Abgeschlossen = true, Ausstellungsdatum = '$ausgestellt' WHERE GutachtenID = $gutachten" ;
		$erg0 = mysql_query( $abf0 )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		unset( $okay ) ;
		}

	include( "../php/head.php" ) ;
	?>

<!------------------------------------------------------------------------------------------>
		<div id="mainstammd">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div class="col_600"><?php echo 'Gutachten ' . $head1 . ' wirklich abschliessen?'; ?></div>

				<div class="clear"></div>
				<div class="col_butt">
					<button type="submit" Name="okay" class="okay_buttn"></button>
					<a href="gutachten_0.php?gutachten=<?php echo $gutachten ; ?>" class="back_buttn"></a>
					</div>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<!-- EOF -->