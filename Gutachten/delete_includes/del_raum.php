<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;

	$errortxt = '' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'copy' ;

	$raum = $_REQUEST[ 'raum' ] ;

	if( isset( $_POST[ 'ja' ] ) ) {
		include( 'raum.php' ) ;
		}
	else {
		$abfrage = "SELECT * FROM $db_raum WHERE RaumID = $raum" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$whng			= $row->InWohnungID ;
			$raumart	= $row->Raumart ;
			}

		$abfrage = "SELECT * FROM $db_wohnung WHERE WohnungID = $whng" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$whngbez		= $row->Bezeichnung ;
			$widmung		= $row->Widmung ;
			$whnglage 	= $row->Lage ;
			$ingebaeude	= $row->InGebaeude ;
			}

		$abfrage = "SELECT * FROM $db_gebaeude WHERE GebaeudeID = $ingebaeude" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$gebbezeich			= $row->Bezeichnung ;
			$gebaeudeart		= $row->Gebaeudeart ;
			}

		$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
		$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			$gz		= $row->GZ ;
			$plz	= $row->PLZ ;
			$ort	= $row->Ort ;
			$str	= $row->Strasse ;
			}


		$head1 = $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
		$head2 = $gebbezeich . ', ' . $gebaeudeart ;
		$head3 = $whngbez . ', ' . $widmung . ', ' . $whnglage ;
		$head4 = $raumart ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../../php/head.php" ) ; ?>

		<div id="mainstammd">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="del_was">
					<div class="b5tabrow">
						<div class="col_60mr">Raum</div>
						<div class="col_190"><?php echo $head4 ; ?></div>
						</div>
					<div class="b5tabrow">
						<div class="col_60mr">WE-Objekt</div>
						<div class="col_190"><?php echo $head3 ; ?></div>
						</div>
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
						<a href="../gutachten_b4.php" class="back_buttn"></a>
						</div>
					</div> <!-- del_was -->

				<div class="errmsg"><?php echo $errortxt ; ?></div>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<!-- EOF -->