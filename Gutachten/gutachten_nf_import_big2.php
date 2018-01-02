<?php
	session_start( ) ;
	include( '../php/DBselect.php' ) ;
	include( '../php/Abmeld.php' ) ;

	if( isset( $_SESSION[ 'anmelden' ] ) ) {
		$anmelden = $_SESSION[ 'anmelden' ] ;
		$ok = true ;
		}
	else {
		$ok = false ;
		}

if( $ok ) {
	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = '1' ;

var_dump(iconv_get_encoding('all'));

	$gutachtenid = $_REQUEST[ 'gutachten' ] ;

	if( $gutachtenid != 0 ) {
		$neues = false ;
		}  
	else {
		$neues = true ;
		}  

	$bereich = 'Datenimport' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'import' ] ) ) {  // neu
		$fn = $_FILES["file"]["name"] ;
		if( $fn != '' ) {
			$pfad	= '../DataImport/' . $fn ;


//			$frage	= "INSERT INTO Urkundenverzeichnis ( `ID`, `GutachtenID`, `Wer`, `Was`, `Filename`, `Inhalt` ) VALUES( NULL, '$gutachtenid', 'ich', 'Import', '$pfad', '' )" ;
//			$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;


			move_uploaded_file($_FILES["file"]["tmp_name"], $pfad ) ;

			$frage = "TRUNCATE TABLE ImportData" ;
			mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

			$abfrage = "LOAD DATA LOCAL INFILE '$pfad' INTO TABLE ImportData COLUMNS TERMINATED BY ';' LINES TERMINATED BY '\n'
									( @field1, @field2, @field3, @field4, @field5, @field6, @field7, @field8 )
										SET RevitID = @field1, Zuordnung = @field2, Top = @field3, Name = @field4, Ebene = @field5, Belegung = @field6, Flche = REPLACE( @field7, ',', '.' ), SSR = @field8";
			mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;

			$abfrag = "DELETE FROM $db_importbim WHERE TOP = '' OR Flche = 0" ;
			mysql_query( $abfrag ) OR die( "Error: $abfrag <br>". mysql_error() ) ;

//-----------------------------------------
//
//	Platz für Importcheck
//
//-----------------------------------------

			$dat = date( "Y.m.d" ) ;

			if( $gutachtenid == 0 ) {
				$bezeichng		= $_POST[ 'bezng' ] . ' - Importiert' ;
				$revitname		= $bezeichng ;
				$bearbeiter		= $anmelden[ 'id' ] ;;
				$abfrage = "SELECT FirmaID FROM $db_user WHERE UserID = '$bearbeiter'" ;
				$ergeb = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				if( $row = mysql_fetch_object( $ergeb ) ) {
					$faid = $row->FirmaID ;
					}
				else {
					$faid = 1 ;
					}
				$abgeschl			= false ;
				$ausgestellt	= '' ;
				$gz						= $bezeichng ;
				$index				= 'a' ;
				$ez						= '' ;
				$gericht			= '' ;
				$stichtag			= '' ;
				$eigner				= '' ;
				$auftrag			= '' ;
				$zweck				= '' ;
				$zweckgrund		= '' ;
				$besichtigt		= $dat ;
				$grundbuch		= '' ;
				$gst_nr				= '' ;
				$plz					= '' ;
				$ort					= '' ;
				$str					= '' ;
				$erstelltam		= '' ;
				$liegenschaft	= '' ;
				$zufahrtlage	= '' ;
				$beschreibung	= '' ;
				$gutachtn			= '' ;
				$abfrage  = "SELECT * FROM $db_txt WHERE TxtArt = 'Epilog'" ;
				$ergeb = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergeb ) ) {
					$praeambel = $row->txt ;
					}
				$gesetzl			= '' ; ;
				$empfehlgen		= '' ;
				$nichtbefund	= '' ;
				$lage					= '' ;
				$anbindung		= '' ;
				$zugang				= '' ;
				$lagegrundstk	= '' ;
				$nachbarbau		= '' ;
				$ausrichtung	= '' ;
				$oeffentlich	= '' ;
				$gruenraum		= '' ;
				$sonstiges		= '' ;
				$ausstattung	= '' ;
				$abfrage	= "INSERT INTO $db_gutachten (`GutachtenID`, `Bezeichnung`, `RevitBez`, `Firma`, `Bearbeiter`, `Abgeschlossen`, `GZ`, `GZindex`, `EZ`, `Bezirksgericht`, `Bewertungsstichtag`, 
															`Eigentuemer`, `Auftraggeber`, `Zweck`, `BesichtigtAm`, `Grundbuch`, `GST_Nr`, `PLZ`, `Ort`, `Strasse`, `Liegenschaft`, `ZufahrtLage`, `Beschreibung`, 
															`Gutachten`, `Praeambel`, `GesetzlGrundl`, `Empfehlungen`, `NichtBefundet`, `Lage`, `Anbindung`, `Zugang`, `LageGrundstk`, `Nachbarbau`, 
															`Ausrichtung`, `Oeffentliches`, `Gruenraum`, `Sonstiges` )
										VALUES( NULL, '$bezeichng', '$revitname', '$faid', '$bearbeiter', false, '$gz', '$index', '$ez', '$gericht', '$stichtag', 
															'$eigner', '$auftrag', '$zweck', '$besichtigt', '$grundbuch',  '$gst_nr','$plz', '$ort', '$str', '$liegenschaft', '$zufahrtlage', '$beschreibung', 
															'$gutachtn', '$praeambel', '$gesetzl', '$empfehlgen', '$nichtbefund', '$lage', '$anbindung', '$zugang', '$lagegrundstk', '$nachbarbau', 
															'$ausrichtung', '$oeffentlich', '$gruenraum', '$sonstiges' )" ;
				$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				$rs = mysql_query("SELECT LAST_INSERT_ID()");
				$tableid = mysql_result($rs,0) ;

				$abfrage= "SELECT * FROM $db_ugsource WHERE Aktiv AND Deflt" ;
				$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
				while( $row = mysql_fetch_object( $ergebnis ) ) {
					$group	= $row->Gruppe ;
					$was		= $row->Beschreibg ;
					$txt		= $row->Txt ;
					$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
											VALUES( NULL, '$tableid', '$group', '', '$was', '', '', '' )" ;
					$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
					$rw = mysql_fetch_object( $ergeb ) ;
					if( $group == 1 ) {
						$frage2 = "UPDATE $db_gutachten SET Gutachten = '$txt', GesetzlGrundl = '$was' WHERE GutachtenID = $tableid" ;
						$ergeb2 = mysql_query( $frage2 ) OR die( "Error: $frage2 <br>". mysql_error() ) ;
						}
					}
				unset( $_SESSION[ 'gutachtenid' ] ) ;
				$_SESSION[ 'gutachtenid' ] = $tableid ;
				}  //if( $gutachtenid == 0 )

			$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

			$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
			$row = mysql_fetch_object( $ergebnis ) ;
			include( '../php/get_db_gutachten.php' ) ;

			$abfrage = "DELETE FROM $db_allgflaeche WHERE InGutachtenID = '$gutachtenid' AND RevitID > 0" ;
			$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;

			$merk_zuord = '' ;
			$merk_whng = '' ;
			$merk_top = '' ;
			$first = true ;
			$abf1 = "SELECT * FROM $db_importbim ORDER BY RevitID, TOP, Ebene, Belegung" ;
			$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
			while( $rw1 = mysql_fetch_object( $erg1 ) ) {
				$idee		= $rw1->ID ;
				$revit	= $rw1->RevitID ;
				$zuord	= $rw1->Zuordnung ;
				$zuord	= iconv('UTF-8', 'ISO-8859-2', $zuord ) ;
				$top		= $rw1->TOP ;
				$name		= $rw1->Name ;
				$ebene	= $rw1->Ebene ;
				$beleg	= $rw1->Belegung ;
				$beleg	= iconv('UTF-8', 'ISO-8859-2', $beleg ) ;
				$flch		= $rw1->Flche ;
				$flch		= round( $flch, 2 ) ;
				$ssr		= $rw1->SSR ;
				$ssr	= iconv('UTF-8', 'ISO-8859-2', $ssr ) ;

				if( $zuord != $merk_zuord OR $first ) {
					$merk_zuord = $zuord ;
					$first = false ;
					$f1 = "SELECT GebaeudeID FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid AND Bezeichnung = '$zuord'" ;
					$e1 = mysql_query( $f1 ) OR die( "Error: $f1 <br>". mysql_error() ) ;
					$r1 = mysql_fetch_object( $e1 ) ;
					if( $r1 ) {  // Gebd. vorhanden
						$gebid = $r1->GebaeudeID ;
						}
					else {  // insert Gebd.
//						if( $zuord == '' ) $zuord = 'Importiert' ;
						$f1 = "SELECT Max( Reihenfolge ) AS Reihe FROM $db_gebaeude WHERE InGutachtenID = $gutachtenid" ;
						$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
						$r1 = mysql_fetch_object( $e1 ) ;
						$reihenfolge 	= 1 + $r1->Reihe ;
						$f1	= "INSERT INTO $db_gebaeude (`GebaeudeID`, `InGutachtenID`, `RevitBez`, `BesichtigtAm`, `Bezeichnung`, `Beschreibung`, `Baujahr`, `Gebaeudeart`, `Zustand`, 
																			`Dachform`, `Bauweise`, `Aufzug`, `AnzGeschosse`, `NwGebaeude`, `Nutzflaeche`, `Reihenfolge` )
											VALUES( NULL, '$gutachtenid', '', '$dat', '$zuord', '', '', '', '', '', '', false, 0, 0, 0, '$reihenfolge' )" ;
						$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
						$rs = mysql_query("SELECT LAST_INSERT_ID()");
						$gebid = mysql_result ($rs, 0 ) ;
						}  // $e1, Gebd.
					}  // if( $zuord != $merk_zuord ) --> Gebäude


				if( $top != $merk_top ) {
					$merk_top = $top ;
					$f1 = "SELECT WohnungID FROM $db_wohnung WHERE InGebaeude = $gebid AND Bezeichnung = '$top'" ;
					$e1 = mysql_query( $f1 ) OR die( "Error: $f1 <br>". mysql_error() ) ;
					$r1 = mysql_fetch_object( $e1 ) ;
					if( $r1 ) {  // update Wohnung
						$wohnid = $r1->WohnungID ;
						}
					else {  // insert Wohnung
						if( $top != 'Allg.' ) {
//							if( $zuord == '' ) $zuord = 'Importiert' ;
							$f1 = "SELECT Max( Reihenfolge ) AS Reihe FROM $db_wohnung WHERE InGebaeude = $gebid" ;
							$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
							$r1 = mysql_fetch_object( $e1 ) ;
							$reihenfolge = 1 + $r1->Reihe ;
							$f1	= "INSERT INTO $db_wohnung (`WohnungID`, `InGutachten`, `InGebaeude`, `RevitBez`, `WEObjart`, `Bezeichnung`, `Lage`, `Widmung`, `Besichtigungsdatum`, `Zustand`, 
																				`Beschreibung`, `Bewertet`, `Regelwohnung`, `Miete`, `Nutzflaeche`, `Nutzwertanteil`, `Nebenraeume`, `RNW`, `RNWcalc`, `Reihenfolge` )
												VALUES( NULL, '$gutachtenid', '$gebid', '$revit', '$ssr', '$top', '$ebene', '$ssr', '', '', '', false, false, 0, 0, 0, false, 0, 0, '$reihenfolge' )" ;
							$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
							$rs = mysql_query("SELECT LAST_INSERT_ID()");
							$wohnid = mysql_result( $rs, 0 ) ;
							}  // $e1, Wohnung.
						}  // $top != 'Allg.'
					$merk_whng = $wohnid ;
					}  // if( $top != $merk_top ) --> Wohnung


//-----------------------------------------------------
				switch( $name ) {
					case 'Allg.' :
						$f1 = "SELECT Max( Reihenfolge ) AS Reihe FROM $db_allgflaeche WHERE InGutachtenID = $gutachtenid" ;
						$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
						$r1 = mysql_fetch_object( $e1 ) ;
						$reihenfolge 	= 1 + $r1->Reihe ;
						$f1	= "INSERT INTO $db_allgflaeche (`AllgID`, `InGutachtenID`, `RevitID`, `Lage`, `Bereich`, `Allgflaeche`, `Reihenfolge` ) 
											VALUES( NULL, '$gutachtenid', '$revit', '$ebene', '$beleg', '$flch', '$reihenfolge' )" ;
						$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
						break ;
//-----------------------------------------------------
					case 'NF' :
						$f1 = "SELECT WohnungID FROM $db_wohnung WHERE InGutachten = $gutachtenid AND Bezeichnung = '$top'" ;
						$e1 = mysql_query( $f1 ) OR die( "Error: $f1 <br>". mysql_error() ) ;
						$r1 = mysql_fetch_object( $e1 ) ;
						$wohnid = $r1->WohnungID ;

						$f1 = "SELECT RaumID FROM $db_raum WHERE InWohnungID = '$wohnid' AND RevitID = '$revit' AND Raumart = '$beleg'" ;
						$e1 = mysql_query( $f1 ) OR die( "Error: $f1 <br>". mysql_error() ) ;
						$r1 = mysql_fetch_object( $e1 ) ;
						if( $r1 ) {
							$raumid = $r1->RaumID ;
							$f1 = "UPDATE $db_raum SET Lage = '$ebene', Raumart = '$beleg', Flaeche = '$flch' WHERE RaumID = $raumid" ;
							}
						else {
							$f1 = "SELECT Max( Reihenfolge ) AS Reihe FROM $db_raum WHERE InWohnungID = $wohnid" ;
							$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
							$r1 = mysql_fetch_object( $e1 ) ;
							$reihenfolge 	= 1 + $r1->Reihe ;
							$f1 = "INSERT INTO $db_raum (`RaumID`, `RevitID`, `InWohnungID`, `Bezeichnung`, `Lage`, `Raumart`, `Bewertung`, `BewertEinheit`, `Flaeche`, `Nutzwert`, 
																						`Einzelnutzwert`, `Nebenraum`, `Reihenfolge` )
													VALUES ( NULL, '$revit', '$wohnid', '$top', '$ebene', '$beleg', false, '', '$flch', 0, 0, false, '$reihenfolge' )" ;
							}
						$e1 = mysql_query( $f1 )  OR die( "Error: $f1 <br>". mysql_error() ) ;
						break ;
//-----------------------------------------------------
					case 'Z' :
						$zubehoer = true ;
						$ab3 = "SELECT * FROM $db_zubehoer WHERE Zubehoer = '$beleg'" ;
						$e3 = mysql_query( $ab3 )  OR die( "Error: $ab3 <br>". mysql_error() ) ;
						if( $r3 = mysql_fetch_object( $e3 ) ) {
							$zubmin	= $r3->Min;
							$zubxxx	= $r3->ZuSchlag ;
							$zubmax	= $r3->Max ;
							$zubein	= $r3->Einheit ;
							}
						else {
							$zubmin	= 0;
							$zubxxx	= 0 ;
							$zubmax	= 0 ;
							$zubein	= '' ;
							}
						$ab4 = "SELECT ZuxxxID FROM $db_whngzuxxx WHERE InGutachten = '$gutachtenid' AND RevitID = '$revit'" ;
						$er4 = mysql_query( $ab4 )  OR die( "Error: $ab4 <br>". mysql_error() ) ;
						$r4 = mysql_fetch_object( $er4 ) ;
						if( $r4 ) {
							$id = $r4->ZuxxxID ;
							$ab5 = "UPDATE $db_whngzuxxx SET Lage = '$ebene', Bezeichnung = '$beleg', Flaeche = '$flch' WHERE ZuxxxID = $id" ;
							}
						else {
							$ab5 = "INSERT INTO $db_whngzuxxx(`ZuxxxID`, `InGutachten`, `InWohnung`, `RevitID`, `Bezeichnung`, `Min`, `Zuxxx`, `Max`, `Einheit`, `Zubehoer`, `Flaeche`, `Lage`, `Rechtspfleger` ) 
												VALUES( NULL, '$gutachtenid', '$merk_whng', '$revit', '$beleg', '$zubmin', '$zubxxx', '$zubmax', '$zubein', '$zubehoer', '$flch', '$ebene', '$beleg' )" ;
							}
						$er5 = mysql_query( $ab5 )  OR die( "Error: $ab5 <br>". mysql_error() ) ;
						break ;
//-----------------------------------------------------
					case 'ZUS' :
						$zubehoer = false ;
						$ab3 = "SELECT * FROM $db_zuschlaege WHERE Bezeichnung = '$beleg'" ;
						$e3 = mysql_query( $ab3 )  OR die( "Error: $ab3 <br>". mysql_error() ) ;
						if( $r3 = mysql_fetch_object( $e3 ) ) {
							$zubmin	= $r3->Min;
							$zubxxx	= $r3->ZuSchlag ;
							$zubmax	= $r3->Max ;
							$zubein	= $r3->Einheit ;
							}
						else {
							$zubmin	= 0;
							$zubxxx	= 0 ;
							$zubmax	= 0 ;
							$zubein	= '' ;
							}
						$ab4 = "SELECT ZuxxxID FROM $db_whngzuxxx WHERE InGutachten = '$gutachtenid' AND RevitID = '$revit'" ;
						$er4 = mysql_query( $ab4 )  OR die( "Error: $ab4 <br>". mysql_error() ) ;
						$r4 = mysql_fetch_object( $er4 ) ;
						if( $r4 ) {
							$id = $r4->ZuxxxID ;
							$ab5 = "UPDATE $db_whngzuxxx SET Lage = '$ebene', Bezeichnung = '$beleg', Flaeche = '$flch' WHERE ZuxxxID = $id" ;
							}
						else {
							$ab5 = "INSERT INTO $db_whngzuxxx(`ZuxxxID`, `InGutachten`, `InWohnung`, `RevitID`, `Bezeichnung`, `Min`, `Zuxxx`, `Max`, `Einheit`, `Zubehoer`, `Flaeche`, `Lage`, `Rechtspfleger` ) 
												VALUES( NULL, '$gutachtenid', '$merk_whng', '$revit', '$beleg', '$zubmin', '$zubxxx', '$zubmax', '$zubein', '$zubehoer', '$flch', '$ebene', '$beleg' )" ;
							}
						$er5 = mysql_query( $ab5 )  OR die( "Error: $ab5 <br>". mysql_error() ) ;
						break ;
					default:
						break ;
					} // switch
//-----------------------------------------------------

				}  // while rw1
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
//  Aufräumen
//-----------------------------------------------------------------------------------
			$ab6 = "SELECT WohnungID FROM $db_wohnung WHERE InGutachten = $gutachtenid" ;
			$er6 = mysql_query( $ab6 ) OR die( "Error: $ab6 <br>". mysql_error() ) ;
			while( $r6 = mysql_fetch_object( $er6 ) ) {
				$whng = $r6->WohnungID ;
				$ab6a = "SELECT RaumID, RevitID FROM $db_raum WHERE InWohnungID = $whng AND RevitID != ''" ;
				$er6a = mysql_query( $ab6a ) OR die( "Error: $ab6a <br>". mysql_error() ) ;
				while( $r6a = mysql_fetch_object( $er6a ) ) {
					$revit = $r6a->RevitID ;
					$ab6b = "SELECT ID FROM $db_importbim WHERE RevitID = $revit" ;
					$er6b = mysql_query( $ab6b ) OR die( "Error: $ab6b <br>". mysql_error() ) ;
					$r6b = mysql_fetch_object( $er6b ) ;
					if( !$r6b ) {
						$ab6c = "DELETE FROM $db_raum WHERE RevitID = $revit" ;
						$er6c = mysql_query( $ab6c ) OR die( "Error: $ab6c <br>". mysql_error() ) ;
						}  // Import Daten
					}  // Räume


				$abf2 = "SELECT COUNT( RaumID ) AS Anz FROM $db_raum WHERE InWohnungID = $whng" ;
				$erg2 = mysql_query( $abf2 ) OR die( "Error: $abf2 <br>". mysql_error() ) ;
				$row2 = mysql_fetch_object( $erg2 ) ;
				$anz = $row2->Anz ;
				if( $anz == 0 ) {
					$frage = "DELETE FROM $db_whngzuxxx WHERE InWohnung = $whng" ;
					$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
					$frage = "DELETE FROM $db_zuabwhng WHERE ZuAbWhng = $whng" ;
					$ergeb = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
					}
				}  // Wohnungen
//-----------------------------------------------------------------------------------
//  Ende Aufräumen
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
			$errortxt = $fn . ' importiert' ;
			}  //if( $fn != '' )
		else{
			$errortxt = 'Zu importierende Datei w&auml;hlen.' ;
			}  // kein $fn
		}  // import
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------

	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

	if( !$abgeschl ) {
		$gebdeid = $_SESSION[ 'gebdeid' ] ;

		include( "../php/head.php" ) ;
		?>

		<div id="mainstammd">
	
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<?php
				if( $neues ) {
					?>
					<div class="clear"></div>
					<div class="col_60mr">Gutachten</div>
					<input type="text" name="bezng" class="col_220" value="<?php echo $bezng ; ?>">
					<?php
					}
					?>

				<div class="clear"></div>
				<input type="file" name="file" id="file" class="inputfile" />
				<label for="file">Zu importierende Daten w&auml;hlen</label>

				<div id="col_buttons">
					<button type="submit" Name="import" class="import_buttn"></button>
					<a href="gutachten.php" class="back_buttn"></a>
					<div class="col_buttons"><?php echo $errortxt ; ?></div>
					</div> <!-- col_buttons -->

				</form>
			</div><!-- main -->
		<?php
		}
		?>
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->