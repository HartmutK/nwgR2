<?php
	session_start( ) ;
	include( '../php/DBselect.php' ) ;
	include( '../php/Abmeld.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'Export' ;

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

	if( isset( $_REQUEST[ 'gutachten' ] ) ) {
		$gutachtenid = $_REQUEST[ 'gutachten' ] ;
		}
	else {
		$gutachtenid = 0 ;
		}

if( $ok AND $gutachtenid > 0 ) {
	$bereich = 'Datenexport' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	$gut8	= $gz . '-' . $index ;
	$daten = '' ;

	$fname = 'Export__' . $gut8 . '__' . $str . ', ' . $plz . '-' . $ort . '.csv' ;

	if( isset( $_POST[ 'export' ] ) ) {  // export
		unset( $_POST[ 'export' ] ) ;
		$bckup = $_POST[ 'bckup' ] ;

		$abfrag = "SELECT * FROM $db_wohnung WHERE InGutachten = $gutachtenid ORDER BY Reihenfolge" ;
		$ergeb = mysql_query( $abfrag ) OR die( "Error: $abfrag <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb ) ) {
			$wohnid	= $row->WohnungID ;
			$revit	= $row->RevitBez ;
			$bezch	= $row->Bezeichnung ;
			$widmg	= $row->Widmung ;
			$flch		= $row->Nutzflaeche ;
			$flch 	= number_format( $flch, 2, ',', '.' ) ;
			$ebene	= $row->Lage ;
			$haus		= $row->InGebaeude ;
			$abf = "SELECT Bezeichnung FROM $db_gebaeude WHERE GebaeudeID = $haus" ;
			$erg= mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			while( $rw = mysql_fetch_object( $erg ) ) {
				$wng	= $rw->Bezeichnung ;
				}

			$abf = "SELECT * FROM $db_raum WHERE InWohnungID = $wohnid ORDER BY Reihenfolge" ;
			$erg= mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			while( $rw = mysql_fetch_object( $erg ) ) {
				$revt	= $rw->RevitID ;
				$eben	= $rw->Lage ;
				$art	= $rw->Raumart ;
				$flch	= $rw->Flaeche ;
				$flch = number_format( $flch, 2, ',', '.' ) ;
				$nw		= $rw->Nutzwert ;
				$nw		= number_format( $nw, 2, ',', '.' ) ;
				$enw	= $rw->Einzelnutzwert ;
				$enw	= number_format( $enw, 2, ',', '.' ) ;
				$daten	= $daten . $revt . ';' . $wng . ';' . $bezch . ';NF;' . $eben . ';' . $art . ';' . $flch . ';' . $nw . ';' . $enw . PHP_EOL ;
				}

			$abf = "SELECT * FROM $db_whngzuxxx WHERE InWohnung = $wohnid" ;
			$erg= mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
			while( $rw = mysql_fetch_object( $erg ) ) {
				$revt	= $rw->RevitID ;
				$widm	= $rw->Bezeichnung ;
				$flch	= $rw->Flaeche ;
				$flch = number_format( $flch, 2, ',', '.' ) ;
				$zubeh = $rw->Zubehoer ;
				if( $zubeh ) {
					$zub	= 'Z' ;
					}
				else {
					$zub	= 'ZUS' ;
					}
				$eben	= $rw->Lage ;
				$daten	= $daten . $revt . ';' . $wng . ';' . $bezch .  ';' . $zub . ';' . $ebene . ';' . $widm . ';' . $flch . ';0;0' . PHP_EOL ;
				}
			}

		$abfrag = "SELECT * FROM $db_allgflaeche WHERE InGutachtenID = $gutachtenid" ;
		$ergeb = mysql_query( $abfrag ) OR die( "Error: $abfrag <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb ) ) {
			$ebene		= $row->Lage ;
			$bereich	= $row->Bereich ;
			$flch 		= $row->Allgflaeche ;
			$flch 		= number_format( $flch, 2, ',', '.' ) ;
			$daten		= $daten . $revt . ';' .  ';Allg.;Allg.;' . $ebene . ';' . $widm . ';' . $flch . ';0;0' . PHP_EOL ;
			}

		header('Content-Type: x-type/octtype');
		header('Content-Length: ' . strlen($daten)); 
		header( "Content-Disposition: attachment; filename='$fname'"); 

		$errortxt = $fname . ' exportiert' ;

		print $daten ; 

		}  // export


//--------------------------------------------------------------------------------------

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

	include( "../php/head.php" ) ;
	?>

	<div id="mainexport">
	
		<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >

			<div class="clear"></div>
			<div class="trin">
				<div class="exportfile">Gutachten: <?php echo $gut8 ; ?></div>
				</div>

			<div class="clear"></div>
<!--			<div class="trin">
				<div class="col1">Backup j/n</div>
				<div class="colyesno"><input type=checkbox <?php if( $bckup == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="bckup"/></div>
				</div>
-->
			<div id="col_buttons">
				<button type="submit" Name="export" class="export_buttn"></button>
				<a href="gutachten.php?&amp;seite='anfang1'" class="back_buttn"></a>
				<div class="errmsg"><?php echo $errortxt ; ?></div>
				</div> <!-- col_buttons -->

			</form>
		</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->