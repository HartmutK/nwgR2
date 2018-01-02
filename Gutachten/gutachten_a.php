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
	$_SESSION[ 'navi' ] = 'A' ;

	if( isset( $_REQUEST[ 'wasid' ] ) ) {
		$tableid = $_REQUEST[ 'wasid' ] ;
		unset( $_SESSION[ 'gutachtenid' ] ) ;
		$_SESSION[ 'gutachtenid' ] = $tableid ;
		}

	if( isset( $_POST[ 'miete' ] ) ) {
		$tableid = $_SESSION[ 'gutachtenid' ] ;
		$abf1  = "SELECT * FROM Wohnungen WHERE InGutachten = $tableid" ;
		$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
		while( $row1 = mysql_fetch_object( $erg1 ) ) {
			$id = $row1->WohnungID ;
			$widm = $row1->Widmung ;
			$flch = $row1->Nutzflaeche ;
			if( $widm == 'Wohnung' ) {
				$miete = $flch ;
				}
			else {
				$miete = 0.4 * $flch ;
				}
			$abf2 = "UPDATE Wohnungen SET Miete = $miete WHERE WohnungID = $id" ;
			$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
			$row = mysql_fetch_object( $erg2 ) ;
			}
		}
		
	if( isset( $_POST[ '1sub' ] ) ) {	
		$gz					= $_POST[ 'gz' ] ;
		$index			= $_POST[ 'index' ] ;
		$ez					= $_POST[ 'ez' ] ;
		$gst_nr			= $_POST[ 'gst_nr' ] ;

		$gemeinde = 'xxx' ;
		$gericht = 'yyy' ;
		$grundbuch		= $_POST[ 'grundbuch' ] ;
		$abfrage = "SELECT * FROM $db_kastral WHERE Nummer = '$grundbuch'" ;
		$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb ) ) {
			$gemeinde = $row->Gemeinde ;
			$gericht = $row->Gericht ;
			}

		$ort					= '' ;
		$gst_nr				= $_POST[ 'gst_nr' ] ;
		$plz					= $_POST[ 'plz' ] ;
		$abfrage = "SELECT * FROM $db_lpo WHERE PLZ = '$plz'" ;
		$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergeb ) ) {
			$ort = $row->Ort ;
			}
		$str				= $_POST[ 'str' ] ;

		$tableid = $_SESSION[ 'gutachtenid' ] ;
		$abfrage = "UPDATE $db_gutachten SET GZ = '$gz', GZindex = '$index', EZ = '$ez', GST_Nr = '$gst_nr', Grundbuch = '$grundbuch', 
										Bezirksgericht = '$gericht', PLZ = '$plz', Ort = '$ort', Strasse = '$str' WHERE GutachtenID = $tableid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Gutachtendaten ge&auml;ndert' ;
		}

	$tableid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $tableid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		unset( $_SESSION[ 'gutachtenid' ] ) ;
		$_SESSION[ 'gutachtenid' ] = $tableid ;
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

				<?php
				if( !$abgeschl ) {
					?>
					<div id="buttnbox">
						<button type="submit" Name="1sub" class="okay_buttn"></button>
<!--				<button type="submit" Name="miete" class="back_buttn">Set Miete</button>-->
						<div class="errmsg"><?php echo $errortxt ; ?></div>
						</div>
					<?php
					}
					?>

				<label for="gz" class="input_label_a">GZ / Index</label>
				<input type="text" name="gz" maxlength="50" class="input_item_a240" value="<?php echo $gz ; ?>">

				<?php
				echo '<select name="index" size="1" class="input_item_a50r">'; 
				$abfrage = "SELECT * FROM $db_idx ORDER BY Indx" ;
				$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb ) ) {
					$was = $row->Indx ;
					if( $was == $index ) { echo '<option selected value="' . $was . '">' . $was . '</option>' ; }
					else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
					} //while - array füllen
				echo'</select>';
				?>

				<label for="ez" class="input_label_a">EZ</label>
				<input type="text" name="ez" maxlength="15" class="input_item_a300" value="<?php echo $ez ; ?>">
				<label for="gst_nr" class="input_label_a">Gst-Nr.</label>
				<input type="text" name="gst_nr" maxlength="50" class="input_item_a300" value="<?php echo $gst_nr ; ?>">
				<label for="grundbuch" class="input_label_a">Grundbuch</label>
				<?php
				echo '<select name="grundbuch" size="1" class="input_item_a50l">'; 
				$abfrage = "SELECT * FROM $db_kastral ORDER BY Nummer" ;
				$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb ) ) {
					$was = $row->Nummer ;
					if( $was == $grundbuch) { 
						echo '<option selected value="' . $was . '">' . $was . '</option>' ;
						$gemeinde = $row->Gemeinde ;
						$gericht = $row->Gericht ;
						}
					else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
					} //while - array füllen
				echo'</select>';
				?>
				<input type="text" disabled class="input_item_a230" value="<?php echo $gemeinde ; ?>">

				<label for="gericht" class="input_label_a">Bezirksgericht</label>
				<input type="text" disabled class="input_item_a300" value="<?php echo $gericht ; ?>">
				<label for="plz" class="input_label_a">PLZ / Ort</label>
				<?php
				echo '<select name="plz" size="1" class="input_item_a50l">'; 
				$abfrage = "SELECT * FROM $db_lpo ORDER BY PLZ" ;
				$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb ) ) {
					$was = $row->PLZ ;
					if( $was == $plz ) { 
						echo '<option selected value="' . $was . '">' . $was . '</option>' ;
						$ort = $row->Ort ;
						}
					else { 	echo '<option value="' . $was . '">' . $was . '</option>'; }
					} //while - array füllen
				echo'</select>';
				?>
				<input type="text" disabled class="input_item_a230" value="<?php echo $ort; ?>">
				<label for="str" class="input_label_a">Strasse</label>
				<input type="text" name="str" maxlength="50" class="input_item_a300" value="<?php echo $str ; ?>">
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->