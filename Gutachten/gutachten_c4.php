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
	$_SESSION[ 'navi' ] = 'C.4' ;

	$tableid 	= $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( isset( $_POST[ 'detail' ] ) ) {
		$whnid = $_POST[ 'detail' ] ;
		unset( $_SESSION[ 'wohnungid' ] ) ;
		$abfr = "SELECT * FROM $db_wohnung WHERE WohnungID = $whnid" ;
//		$abfr = "SELECT * FROM $db_wohnung WHERE InGutachten = $tableid AND WohnungID = '$widmg'" ;
		$erg = mysql_query( $abfr )  OR die( "Error: $abfr <br>". mysql_error() ) ;
		while( $rwd = mysql_fetch_object( $erg ) ) {
			$eidie	= $rwd->WohnungID ;
			$widmg	= $rwd->Widmung ;
			$bez		= $rwd->Bezeichnung ;
			$wglage	= $rwd->Lage ;
			$rnw		= $rwd->RNW ;
//			$_SESSION[ 'wohnungid' ] = $wohnungid ;
			}
		$savebuttn = true ;
		}

	if( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}

	if( isset( $_POST[ 'save' ] )  ) {
		$whnid = $_POST[ 'save' ] ;
		$rnw = $_POST[ 'rnw' ] ;

//		$wohnungid = $_SESSION[ 'wohnungid' ] ;
//		$abfrage = "UPDATE $db_wohnung SET Bezeichnung = '$wgnr', Lage = '$wglage', Widmung = '$widm', Miete = '$mieten', RNW = '$rnw' WHERE WohnungID = $wohnungid" ;
		$abfrage = "UPDATE $db_wohnung SET RNW = '$rnw' WHERE WohnungID = $whnid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$widmg	= '' ;
		$wglage	= '' ;
		$rnw		= 0 ;
		$savebuttn = false ;
		unset( $_POST[ 'save' ] ) ;
		}

// RNW holen
	unset( $raum ) ;
	$frage2 = "SELECT WohnungID, Bezeichnung, Widmung, Lage FROM $db_wohnung WHERE InGutachten = $tableid AND Regelwohnung" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb2 ) ) {
		$whnid = $row->WohnungID ;
		$bezch = $row->Bezeichnung ;
		$wdmg	 = $row->Bezeichnung . ' (Regelwohnung)' ;
		$whglage = $row->Lage ;
		$raum [ ] = array( 'whnid'=>$whnid, 'bezch'=>$bezch, 'widm'=>$wdmg, 'lage'=>$whglage, 'rnwrt'=>'1,00', 'rwhng'=>true ) ;
		}

//	$frage2 = "SELECT DISTINCT( Widmung ), Lage, RNW FROM $db_wohnung WHERE InGutachten = $tableid AND Widmung != 'Wohnung'" ;
	$frage2 = "SELECT WohnungID, Bezeichnung, Widmung, Lage, RNW FROM $db_wohnung WHERE InGutachten = $tableid AND Widmung != 'Wohnung' ORDER BY Reihenfolge" ;
	$ergeb2 = mysql_query( $frage2 )  OR die( "Error: $frage2 <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergeb2 ) ) {
		$whnid 			= $row->WohnungID ;
		$bezch			= $row->Bezeichnung ;
		$widmung		= $row->Widmung ;
		$whnglage 	= $row->Lage ;
		$rnwwohnung	= $row->RNW ;
		if( $whnglage == '' ) { $whnglage = '-' ; }
		$raum [ ] = array( 'whnid'=>$whnid, 'bezch'=>$bezch, 'widm'=>$widmung, 'lage'=>$whnglage, 'rnwrt'=>$rnwwohnung, 'rwhng'=>false ) ;
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

		<div class="clear"></div>

		<div id="mainc4">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainc4_oben">
					<?php
					if( !$abgeschl ) {
						?>
						<div class="trin">
							<div class="col1"><?php echo $widmg ; ?></div>
							<div class="col1"><?php echo $bez ; ?></div>
							<div class="col2"><?php echo $wglage ; ?></div>
							<div class="col3"><input type="number" class="col3" maxlength="6" name="rnw" step="0.01" value="<?php echo $rnw ; ?>"></div>
							<div class="col_buttons">
								<?php
								if( $savebuttn ) {
									?>
									<button type="submit" name="save"	class="okay_buttn" value="<?php echo $eidie ; ?>"></button>
									<button type="submit" name="bck" class="back_buttn"></button>
									<?php
									}
									?>
									<div class="col_buttons"><?php echo $errortxt ; ?></div>
									</div>
							<?php
							}  // if...
							?>
						</div>

					<div class="clear"></div>
					<div class="trhd">
						<div class="col1">Widmung</div>
						<div class="col1">Bezeichnung</div>
						<div class="col2">Lage</div>
						<div class="col3">RNW</div>
						</div>
					</div> <!-- mainc4_oben -->

				<div class="clear"></div>
				<div id="mainc4_unten">

					<?php
					$i = 0 ;
					$anz_raum = count( $raum ) ;
					while( $i < $anz_raum ) {
						$whnid	= $raum [ $i ][ 'whnid' ] ;
						$widm		= $raum [ $i ][ 'widm' ] ;
						$bezch	= $raum [ $i ][ 'bezch' ] ;
						$lage		= $raum [ $i ][ 'lage' ] ;
						$rwhng	= $raum [ $i ][ 'rwhng' ] ;
						$rnwrt	= $raum [ $i ][ 'rnwrt' ] ;
						$rnwrt  = number_format( $rnwrt , 2, ',', '.' ) ;
						?>
						<div class="tr">
							<div class="col1"><?php echo $widm ; ?></div>
							<div class="col1"><?php echo $bezch ; ?></div>
							<div class="col2"><?php echo $lage ; ?></div>
							<div class="col3"><?php echo $rnwrt ; ?></div>
							<div class="col_buttons">
								<?php
								if( !$abgeschl and !$rwhng ) {
									?>
									<button type="submit" name="detail"	class="edit_buttn" value="<?php echo $whnid ; ?>"></button>
									<?php
									}
									?>
								</div>
							</div>
						<?php
						$i++ ;
						}
						?>
					</div> <!-- mainc4_unten-->
				</form>
			</div><!-- mainc4 -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->