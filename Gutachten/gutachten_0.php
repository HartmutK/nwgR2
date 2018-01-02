<?php
	session_start( ) ;

	include( '../php/DBselect.php' ) ;
	include( '../php/GlobalProject.php' ) ;

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

	unset( $_SESSION[ 'topic' ] ) ;
	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = '1' ;

	$gutachten = $_REQUEST[ 'gutachten' ] ;

	$abfrage  = "SELECT * FROM $db_gutachten WHERE GutachtenID = $gutachten" ;
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
?>

<?php include( "../php/head.php" ) ; ?>

		<div id="mainprint">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div class="clear0"></div>
				<?php
				if( !$abgeschl ) {
					?>
					<div class="section">
						<hd>Vorabzub</hd>
						<div><a href="../GutachtenDruck/Paragraph_6.php?wie=1" target="_blank" class="buttn">&sect;6</a></div>
						<div><a href="../GutachtenDruck/Paragraph_9.php?wie=3" target="_blank" class="buttn">&sect;9</a></div>
<!--						<div><a href="../GutachtenDruck/Paragraph_6_getfromDB.php" target="_blank" class="buttn">PDF</a></div> -->
						</div>
					<?php
					}
				else {
					?>
					<div class="section"></div>
					<?php
					}
					?>
				<div class="section">
					<hd>Allgemeines</hd>
					<div><a href="../GutachtenDruck/A.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">A Allgemeines</a></div>
					<div><a href="../GutachtenDruck/A1.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">A.1 AuftraggeberInnen</a></div>
					<div><a href="../GutachtenDruck/A2.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">A.2 AuftragnehmerInnen</a></div>
					<div><a href="../GutachtenDruck/A3.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">A.3 Zweck des Gutachtens</a></div>
					<div><a href="../GutachtenDruck/A4.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">A.4 Bewertungsstichtag</a></div>
					</div>
				<div class="section">
					<hd>Befund</hd>
					<div><a href="../GutachtenDruck/B.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B Befund</a></div>
					<div><a href="../GutachtenDruck/B1.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B.1 Grundbuchstand</a></div>
					<div><a href="../GutachtenDruck/B2.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B.2 Beschreibung</a></div>
					<div><a href="../GutachtenDruck/B3.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B.3 Ortsbesichtigungen</a></div>
					<div><a href="../GutachtenDruck/B4.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B.4 Nutzfl&auml;chenberechnung</a></div>
					<div><a href="../GutachtenDruck/B5.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B.5 Grundlagen / Unterlagen</a></div>
					<div><a href="../GutachtenDruck/B6.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">B.6 Plangrundlagen</a></div>
					</div>
				<div class="section">
					<hd>Gutachten</hd>
					<div><a href="../GutachtenDruck/C.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C Gutachten</a></div>
					<div><a href="../GutachtenDruck/C1.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.1 Allgemeines</a></div>
					<div><a href="../GutachtenDruck/C2.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.2 Allgemeinfl&auml;chen</a></div>
					<div><a href="../GutachtenDruck/C3.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.3 Regelwohnung</a></div>
					<div><a href="../GutachtenDruck/C4.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.4 Regelnutzwerte</a></div>
					<div><a href="../GutachtenDruck/C5.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.5 Zubeh&ouml;r</a></div>
					<div><a href="../GutachtenDruck/C6.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.6 Zuschl&auml;ge</a></div>
					<div><a href="../GutachtenDruck/C7.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.7 Zu-/Abschlagsmatrix</a></div>
					<div><a href="../GutachtenDruck/C8.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.8 Nutzwertermittlung</a></div>
					<div><a href="../GutachtenDruck/C9.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.9 Nutzwertberechnung</a></div>
					<div><a href="../GutachtenDruck/C10.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.10 Zusammenfassung</a></div>
					<div><a href="../GutachtenDruck/C11.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.11 Epilog</a></div>
					<div><a href="../GutachtenDruck/Legende.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">C.12 Legende</a></div>
					</div>
				<div class="section">
					<hd>Anh&auml;nge</hd>
					<div><a href="../GutachtenDruck/D1.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">Grundbuchauszug</a></div>
					<div><a href="../GutachtenDruck/B6.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">Grundrisse</a></div>
					<div><a href="../GutachtenDruck/D2.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">Lageplan</a></div>
					<div><a href="../GutachtenDruck/D3.php?was=<?php echo $tableid ; ?>&wie=3"  target="_blank" class="buttn">Fotos</a></div>
					</div>
				<div class="section">
					<hd>Endfassung</hd>
					<?php
					if( !$abgeschl ) {
						?>
						<div><a href="gutachten_0ask.php" class="buttn">Abschliessen?</a></div>
						</div>
						<?php
						}
					else {
						?>
						<div><a href="../GutachtenDruck/Paragraph_6.php?wie=2" target="_blank" class="buttn">&sect;6</a></div>
						<div><a href="../GutachtenDruck/Paragraph_9.php?wie=4" target="_blank" class="buttn">&sect;9</a></div>
						<?php
						}
						?>
<!--				<div class="clear"></div>
				<div class="section">
					<div><a href="../GutachtenDruck/FirstPage.php"  target="_blank" class="buttn">Deckblatt</a></div>
					<div><a href="../GutachtenDruck/LastPage.php"  target="_blank" class="buttn">R&uuml;ckseite</a></div>
					</div> -->
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->