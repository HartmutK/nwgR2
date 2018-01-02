<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;
	include( '../php/get_input_unterlagen.php' ) ;

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

if( $ok ) {
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'B.2.1' ;

// Gutachtendaten holen

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid " ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$gz						= $row->GZ ;
		$index				= $row->GZindex ;
		$plz					= $row->PLZ ;
		$ort					= $row->Ort ;
		$str					= $row->Strasse ;
		$lage					= $row->Lage ;
		$zufahrtlage	= $row->ZufahrtLage ;
		$anbindung		= $row->Anbindung ;
		$zugang				= $row->Zugang ;
		$lagegrundstk	= $row->LageGrundstk ;
		$nachbarbau		= $row->Nachbarbau ;
		$ausrichtung	= $row->Ausrichtung ;
		$oeffentlich	= $row->Oeffentliches ;
		$gruenraum		= $row->Gruenraum ;
		$sonstiges		= $row->Sonstiges ;
		$beschreib		= $row->Beschreibung ;
		$abgeschl			= $row->Abgeschlossen ;
		}

	if( isset( $_POST[ '1sub' ] ) ) {	
		$tableid = $_SESSION[ 'tableid' ] ;
		$lage					= $_POST[ 'lage' ] ;
		$zufahrtlage	= $_POST[ 'zufahrtlage' ] ;
		$anbindung		= $_POST[ 'anbindung' ] ;
		$zugang				= $_POST[ 'zugang' ] ;
		$lagegrundstk	= $_POST[ 'lagegrundstk' ] ;
		$nachbarbau		= $_POST[ 'nachbarbau' ] ;
		$ausrichtung	= $_POST[ 'ausrichtung' ] ;
		$oeffentlich	= $_POST[ 'oeffentlich' ] ;
		$gruenraum		= $_POST[ 'gruenraum' ] ;
		$sonstiges		= $_POST[ 'sonstiges' ] ;
		$gutachtn			= $_POST[ 'gutachtn' ] ;
		$beschreib		= $_POST[ 'beschreib' ] ;

		if( isset( $_POST[ 'wasid' ] ) ) {	
			$gutachtenid = $_POST[ 'wasid' ] ;
			}
		else {
			$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
			}
		$abfrage = "UPDATE $db_gutachten SET Lage = '$lage', ZufahrtLage = '$zufahrtlage', Anbindung = '$anbindung', Zugang = '$zugang', LageGrundstk = '$lagegrundstk', 
									Nachbarbau = '$nachbarbau', Ausrichtung = '$ausrichtung', Oeffentliches = '$oeffentlich', Gruenraum = '$gruenraum', Sonstiges = '$sonstiges', 
									Beschreibung = '$beschreib'
									WHERE GutachtenID = $gutachtenid" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$errortxt = 'Lagedaten ge&auml;ndert' ;
		}

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

		<div id="mainb2">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
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
					<div class="col1">Lage:</div>
					<div><input type="text" name="lage" maxlength="100" class="col2" value="<?php echo $lage ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Zufahrt (PKW, Rad):</div>
					<div><input type="text" name="zufahrtlage" maxlength="100" class="col2" value="<?php echo $zufahrtlage ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">&Ouml;ffentliche Anbindung:</div>
					<div><input type="text" name="anbindung" maxlength="100" class="col2" value="<?php echo $anbindung ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Zugang:</div>
					<div><input type="text" name="zugang" maxlength="100" class="col2" value="<?php echo $zugang; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Lage am Grundst&uuml;ck:</div>
					<div><input type="text" name="lagegrundstk" maxlength="100" class="col2" value="<?php echo $lagegrundstk ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Nachbarbebauung:</div>
					<div><input type="text" name="nachbarbau" maxlength="100" class="col2" value="<?php echo $nachbarbau ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Ausrichtung:</div>
					<div><input type="text" name="ausrichtung" maxlength="100" class="col2" value="<?php echo $ausrichtung ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">&Ouml;ffentliche Einrichtungen:</div>
					<div><input type="text" name="oeffentlich" maxlength="100" class="col2" value="<?php echo $oeffentlich ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Gr&uuml;nraum / Nahversorgung:</div>
					<div><input type="text" name="gruenraum" maxlength="100" class="col2" value="<?php echo $gruenraum ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Sonstiges:</div>
					<div class="col2"><input type="text" name="sonstiges" maxlength="100" class="col2" value="<?php echo $sonstiges ; ?>"></div>
					</div>
				<div class="clear"></div>
				<div class="zeile">
					<div class="col1">Beschreibung:</div>
					<textarea name="beschreib" maxlength="64000" cols="60" rows="12" class="col2" ><?php echo $beschreib ; ?></textarea>
					</div>
				</form>
			</div><!-- mainb2 -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->