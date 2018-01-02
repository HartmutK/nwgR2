<?php
	session_start( ) ;
	include( '../php/DBselect.php' ) ;
	include( '../php/Abmeld.php' ) ;

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
	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'B.2.2' ;

	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

// Gutachtendaten holen
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;
	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}

	if( isset( $_POST[ 'detail' ] ) ) {
		$gebaeudeid = $_POST[ 'detail' ] ;
		$_SESSION[ 'geb_idee' ] = $gebaeudeid ;
		$abfrage = "SELECT * FROM $db_gebaeude WHERE GebaeudeID = '$gebaeudeid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $row = mysql_fetch_object( $ergebnis ) ) {
			include( '../php/get_db_gebaeude.php' ) ;
			$savebuttn = true ;
			}
		}

	elseif( isset( $_POST[ 'neu' ] ) ) {
		$gebbezeich			= '' ;
		$gebaeudeart		= '' ;
		$baujahr				= '' ;
		$bauweise				= '' ;
		$anzgeschosse		= 0 ;
		$aufzug					= false ;
		$gebbeschreib		= '' ;
		$zustand				= '' ;

		$besichtigtam		= '' ;
		$abfrage = "SELECT MAX( BesichtigtAm ) AS lastvisited FROM $db_gebaeude WHERE InGutachtenID = '$gutachtenid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		while( $rw = mysql_fetch_object( $ergebnis ) ) {
			$besichtigtam		= $rw->lastvisited ;
			}
		unset( $_SESSION[ 'geb_idee' ] ) ;
		$savebuttn = true ;
		}

	elseif( isset( $_POST[ 'rauf' ] ) ) {
		$dokid = $_POST[ 'rauf' ] ;
		$abf = "SELECT GebaeudeID, Reihenfolge FROM $db_gebaeude WHERE GebaeudeID = '$dokid'" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$ingebd  = $rw->GebaeudeID ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num - 1 ;
		$abf = "UPDATE $db_gebaeude SET Reihenfolge = $old_num WHERE GebaeudeID = $ingebd AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_gebaeude SET Reihenfolge = $new_num WHERE GebaeudeID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'runter' ] ) ) {
		$dokid = $_POST[ 'runter' ] ;
		$abf = "SELECT GebaeudeID, Reihenfolge FROM $db_gebaeude WHERE GebaeudeID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$rw = mysql_fetch_object( $erg ) ;
		$ingebd  = $rw->GebaeudeID ;
		$old_num = $rw->Reihenfolge ;
		$new_num = $old_num + 1 ;
		$abf = "UPDATE $db_gebaeude SET Reihenfolge = $old_num WHERE GebaeudeID = $ingebd AND Reihenfolge = $new_num" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		$abf = "UPDATE $db_gebaeude SET Reihenfolge = $new_num WHERE GebaeudeID = $dokid" ;
		$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'bck' ] ) ) {
		unset( $_POST[ 'bck' ] ) ;
		$savebuttn = false ;
		}

	elseif( isset( $_POST[ 'save' ] ) ) {
		include( '../php/get_input_gebaeude.php' ) ;
		if( $gebbezeich == '' ) {
			$gebbezeich = '-' ;
			}
		if( isset( $_SESSION[ 'geb_idee' ] ) ) {
			$gebaeudeid = $_SESSION[ 'geb_idee' ] ;
			$abfrage = "UPDATE $db_gebaeude SET InGutachtenID = '$ingutachten', BesichtigtAm = '$besichtigtam', Bezeichnung = '$gebbezeich', 
										Beschreibung = '$gebbeschreib', Baujahr = '$baujahr', Gebaeudeart = '$gebaeudeart', Zustand = '$zustand',  
										Dachform = '$dachform', Bauweise = '$bauweise', Aufzug = '$aufzug', 
										AnzGeschosse = '$anzgeschosse'
										WHERE GebaeudeID = '$gebaeudeid'" ;
			$errortxt = 'Objektdaten ge&auml;ndert' ;
			unset( $_SESSION[ 'geb_idee' ] ) ;
			}
		else {
			$abf = "SELECT MAX( Reihenfolge ) AS MaxNr FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid" ;
			$erg = mysql_query( $abf )  OR die( "Error: $abf <br>". mysql_error() ) ;
			$rw = mysql_fetch_object( $erg ) ;
			$next_doc = 1 + $rw->MaxNr ;

			$abfrage	= "INSERT INTO $db_gebaeude (`GebaeudeID`, `InGutachtenID`, `BesichtigtAm`, `Bezeichnung`, `Beschreibung`, `Baujahr`, `Gebaeudeart`, `Zustand`, 
																`Dachform`, `Bauweise`, `Aufzug`, `AnzGeschosse`, `Reihenfolge` )
										VALUES( NULL, '$gutachtenid', '$besichtigtam', '$gebbezeich', '$gebbeschreib', '$baujahr', '$gebaeudeart', '$zustand', 
																'', '$bauweise', '$aufzug', '$anzgeschosse', '$next_doc' )" ;
			$errortxt = 'Objekt gespeichert' ;
			}
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$gebbezeich			= '' ;
		$gebaeudeart		= '' ;
		$baujahr				= '' ;
		$bauweise				= '' ;
		$anzgeschosse		= 0 ;
		$aufzug					= false ;
		$besichtigtam		= '' ;
		$gebbeschreib		= '' ;
		$zustand				= '' ;
		$savebuttn			= false ;
		}

	unset( $gebaeude ) ;
	$abfrage = "SELECT * FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid ORDER BY Reihenfolge ASC" ;

	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$gebde		= $row->GebaeudeID ;
		$bezeich	= $row->Bezeichnung ;
		$gebart		= $row->Gebaeudeart ;
		$besicht	= $row->BesichtigtAm;
		$gebaeude [ ] = array( 'gebde'=>$gebde, 'bezeich'=>$bezeich, 'gebart'=>$gebart, 'besicht'=>$besicht) ;
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

		include( "../php/head.php" ) ; ?>

		<div class="clear"></div>
		<div id="mainb22">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div class="clear"></div>
				<div id="mainb22_oben">
					<?php
					if( !$abgeschl ) {
						?>
						<div class="clear"></div>
						<div class="trin">
							<div class="col_label">Einzelobjekt:</div>
							<div class="col_input2"><input type="text" name="gebbezeich" class="col_input1a" value="<?php echo $gebbezeich ; ?>"></div>
							<div class="col_label">Bauweise:</div>
							<div class="col_input2">
								<?php
								echo '<select name="bauw" size="1" class="col_input1">'; 
								$abfrage = "SELECT Bauweise FROM $db_bauweise ORDER BY Bauweise" ;
								$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $ergeb ) ) {
									$bauw = $row->Bauweise ;
									if( $bauw == $bauweise ) { 	echo '<option selected value="' . $bauw . '">' . $bauw . '</option>'; }
									else { 	echo '<option value="' . $bauw . '">' . $bauw . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col_label">Sonstiges:</div>
							<div class="col_input2"><input type="text" name="gebbeschreib" class="col_input1a" value="<?php echo $gebbeschreib ; ?>"></div>
							<div class="col_buttons">
								<button type="submit" name="save" class="okay_buttn"></button>
								<?php
								if( $savebuttn ) {
									?>
									<button type="submit" name="bck" class="back_buttn"></button>
									<?php
									}
								?>
								<div class="col_buttons"><?php echo $errortxt ; ?></div>
								</div>  <!-- buttons -->
							</div>  <!-- trin -->
						<div class="clear"></div>
						<div class="trin">
							<div class="col_label">Objektart:</div>
							<div class="col_input2">
								<?php
								echo '<select name="gart" size="1" class="col_input1">'; 
								$abfrage = "SELECT Art FROM $db_gebaeudeart ORDER BY Art" ;
								$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $ergeb ) ) {
									$gart = $row->Art ;
									if( $gart == $gebaeudeart ) { 	echo '<option selected value="' . $gart . '">' . $gart . '</option>'; }
									else { 	echo '<option value="' . $gart . '">' . $gart . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col_label">Geschossanzahl:</div>
							<div class="col_input2"><input type="number" name="anzgeschosse" step="1" class="col_input1a" value="<?php echo $anzgeschosse ; ?>"></div>
							<div class="col_label">Aufzug</div>
							<div class="col_aufzug"><input type=checkbox <?php if( $aufzug == 1 ) { echo 'checked' ; } else { echo 'unchecked' ; } ?> name="aufzug"/></div>
							</div>
						<div class="clear"></div>
						<div class="trin">
							<div class="col_label">Errichtung:</div>
							<div class="col_input2"><input type="number" name="baujahr" step="1" class="col_input1a" value="<?php echo $baujahr ; ?>"></div>
							<div class="col_label">Zustand:</div>
							<div class="col_input2">
								<?php
								echo '<select name="zust" size="1" class="col_input1">'; 
								$abfrage = "SELECT Zustand FROM $db_zustand ORDER BY Zustand" ;
								$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
								while( $row = mysql_fetch_object( $ergeb ) ) {
									$zust = $row->Zustand ;
									if( $zust == $zustand) { 	echo '<option selected value="' . $zust . '">' . $zust . '</option>'; }
									else { 	echo '<option value="' . $zust . '">' . $zust . '</option>'; }
									} //while - array füllen
								echo'</select>';
								?>
								</div>
							<div class="col_label">Besichtigt am:</div>
							<div class="col_input2"><input type="date" name="besichtigtam" class="col_input1a" value="<?php echo $besichtigtam ; ?>"></div>
							</div>
						<?php
						}
					else {
						if( $gebbeschreib == '' ) { $gebbeschreib = '-' ; }
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_label">Einzelobjekt</div>
							<div class="col_input2"><?php echo $gebbezeich ; ?></div>
							<div class="col_label">Bauweise</div>
							<div class="col_input2"><?php echo $bauweise ; ?></div>
							<div class="col_label">Sonstiges</div>
							<div class="col_input2"><?php echo $gebbeschreib ; ?></div>
							<div class="col_buttons"><button type="submit" name="bck" class="back_buttn"></button></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_label">Objektart</div>
							<div class="col_input2"><?php echo $gebaeudeart ; ?></div>
							<div class="col_label">Geschossanzahl</div>
							<div class="col_input2"><?php echo $anzgeschosse ; ?></div>
							<div class="col_label">Aufzug</div>
							<div class="col_aufzug"><?php echo $aufzug ; ?></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_label">Errichtung</div>
							<div class="col_input2"><?php echo $baujahr ; ?></div>
							<div class="col_label">Zustand</div>
							<div class="col_input2"><?php echo $zustand ; ?></div>
							<div class="col_label">Besichtigt am</div>
							<div class="col_input2"><?php echo $besichtigtam ; ?></div>
							</div>
						<?php
						}
					?>
					<div class="clear"></div>
					<div class="trhd">
						<div class="col1">Bezeichnung</div>
						<div class="col2">Art</div>
						<div class="col3">Besichtigt am</div>
						</div>
					</div>  <!-- mainb22_oben -->

				<div class="clear"></div>
				<div id="mainb22_unten">
					<?php
					$i = 0 ;
					$anz_gebaeude = count( $gebaeude ) ;
					while( $i < $anz_gebaeude ) {
						$gebde		= $gebaeude [ $i ][ 'gebde' ] ;
						$ingut8		= $gebaeude [ $i ][ 'ingut8' ] ;
						$bezeich	= $gebaeude [ $i ][ 'bezeich' ] ;
						$gebart		= $gebaeude [ $i ][ 'gebart' ] ;
						$besicht	= $gebaeude [ $i ][ 'besicht' ] ;
						$besicht	= date( 'Y.m.d', strtotime( $besicht ) ) ;
						?>
						<div class="clear"></div>
						<div class="tr">
							<div class="col1"><?php echo $bezeich ; ?></div>
							<div class="col2"><?php echo $gebart ; ?></div>
							<div class="col3"><?php echo $besicht ; ?></div>
							<div class="col_buttons">
								<button type="submit" name= "detail"	class="edit_buttn" value="<?php echo $gebde ; ?>"></button>
								<?php
								if( !$abgeschl ) {
									?>
									<a href="copy_proc/cpy_gebaude.php?gebaude=<?php echo $gebde  ; ?>" class="copy_buttn"></a>
									<a href="delete_includes/del_gebaude.php?gebaude=<?php echo $gebde ; ?>" class="delete_buttn"></a>
									<button type="submit" name="rauf"	class="rauf_buttn" value="<?php echo $gebde ; ?>"></button>
									<button type="submit" name="runter"	class="runter_buttn" value="<?php echo $gebde ; ?>"></button>
									<?php
									}
									?>
								</div>
							</div>
						<?php
						$i++ ;
						}
						?>
					</div>  <!-- mainb22_unten -->
				</form>
			</div>  <!-- mainb22 -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->