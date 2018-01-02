<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'copy' ;

	$errortxt = '' ;
	$gebde = $_REQUEST[ 'gebaude' ] ;

	if( isset( $_POST[ 'ja' ] ) ) {
		include( 'gebaeude.php' );
		} //if
	else {
		$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
		$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$gz				= $row->GZ ;
			$plz			= $row->PLZ ;
			$ort			= $row->Ort ;
			$str			= $row->Strasse ;
			}
		$abfrage = "SELECT * FROM $db_gebaeude WHERE GebaeudeID = $gebde" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$gebbezeich			= $row->Bezeichnung ;
			$gebaeudeart		= $row->Gebaeudeart ;
			}

		$head1 = $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
		$head2 = $gebbezeich . ', ' . $gebaeudeart ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../../php/head.php" ) ; ?>

		<div id="mainstammd">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="del_was">
					<div class="b5tabrow">
						<div class="col_60mr">Objekt</div>
						<div class="col_190"><?php echo $head2 ; ?></div>
						</div>
					<div class="b5tabrow">
						<div class="col_60mr">Gutachten</div>
						<div class="col_190"><?php echo $head1 ; ?></div>
						</div>
					<div class="b5tabrow">
						<div class="col_85"><br><br></div>
						</div>
					<div class="b5tabrow">
						<div class="col_85">Wirklich L&ouml;schen?</div>
						<button type="submit" name="ja" class="okay_buttn"></button>
						<a href="../gutachten_b2_2.php" class="back_buttn"></a>
						</div>
					</div> <!-- del_was -->

				<div class="errmsg"><?php echo $errortxt ; ?></div>
				</form>
			</div><!-- main -->

		</div>  <!-- container -->
	</body>
	</html>
<!-- EOF -->