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

	$topic = 'Systemdaten verwalten' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'Stammdat' ;

	$topic = 'Firmendaten verwalten' ;
	$errortxt = '' ;

	$abfrage = "SELECT * FROM $db_system WHERE ID = 1" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	$row = mysql_fetch_object( $ergebnis ) ;
	$page_W					= $row->PageWidth ;
	$page_H					= $row->PageHeight ;
	$titlemargin_T	= $row->TitleMarginTop ;
	$titlemargin_R	= $row->TitleMarginRight ;
	$titlemargin_B	= $row->TitleMarginBottom ;
	$titlemargin_L	= $row->TitleMarginLeft ;
	$titlpicleft_W	= $row->TitlePicLeft_Width ;
	$titlpicright_W	= $row->TitlePicRight_Width ;
	$pagemargin_T		= $row->PageMarginTop ;
	$pagemargin_R		= $row->PageMarginRight ;
	$pagemargin_B		= $row->PageMarginBottom ;
	$pagemargin_L		= $row->PageMarginLeft ;
	$posp						= $row->Pos_P ;
	$posgz					= $row->Pos_GZ ;
	$linemargin			= $row->Linemargin ;
	$schrift				= $row->Schrift ;
	$schriftsize		= $row->Schriftsize ;
	$textbreite 		= $row->Textbreite ;
	$zeilenhoehe		= $row->Zeilenhoehe ;
	$seitenhoehe		= $row->Seitenhoehe ;
	$verybigsize		= $row->VeryBig ;
	$h1size					= $row->H1Size ;
	$h2size					= $row->H2Size ;
	$h3size					= $row->H3Size ;
	$h1hoehe				= $row->H1Height ;
	$h2hoehe				= $row->H2Height ;
	$h3hoehe				= $row->H3Height ;

	if( isset( $_POST[ 'save' ] ) ) {
		$page_W					= $_POST[ 'page_W' ] ;
		$page_H					= $_POST[ 'page_H' ] ;
		$titlemargin_T	= $_POST[ 'titlemargin_T' ] ;
		$titlemargin_R	= $_POST[ 'titlemargin_R' ] ;
		$titlemargin_B	= $_POST[ 'titlemargin_B' ] ;
		$titlemargin_L	= $_POST[ 'titlemargin_L' ] ;
		$pagemargin_T		= $_POST[ 'pagemargin_T' ] ;
		$pagemargin_R		= $_POST[ 'pagemargin_R' ] ;
		$pagemargin_B		= $_POST[ 'pagemargin_B' ] ;
		$pagemargin_L		= $_POST[ 'pagemargin_L' ] ;
		$posp						= $_POST[ 'posp' ] ;
		$posgz					= $_POST[ 'posgz' ] ;
		$linemargin			= $_POST[ 'linemargin' ] ;
		$schrift				= $_POST[ 'schrift' ] ;
		$schriftsize		= $_POST[ 'schriftsize' ] ;
		$textbreite 		= $_POST[ 'textbreite' ] ;
		$zeilenhoehe		= $_POST[ 'zeilenhoehe' ] ;
		$seitenhoehe		= $_POST[ 'seitenhoehe' ] ;
		$verybigsize		= $_POST[ 'verybigsize' ] ;
		$h1size					= $_POST[ 'h1size' ] ;
		$h2size					= $_POST[ 'h2size' ] ;
		$h3size					= $_POST[ 'h3size' ] ;
		$h1hoehe				= $_POST[ 'h1hoehe' ] ;
		$h2hoehe				= $_POST[ 'h2hoehe' ] ;
		$h3hoehe				= $_POST[ 'h3hoehe' ] ;
		$abfrage = "UPDATE $db_system SET PageWidth = '$page_W', PageHeight = '$page_H', 
									TitleMarginTop = '$titlemargin_T', TitleMarginRight = '$titlemargin_R', TitleMarginBottom = '$titlemargin_B', TitleMarginLeft = '$titlemargin_L', 
									TitlePicLeft_Width = '$titlpicleft_W', TitlePicRight_Width= '$titlpicright_W', 
									PageMarginTop = '$pagemargin_T', PageMarginRight = '$pagemargin_R', PageMarginBottom = '$pagemargin_B', PageMarginLeft = '$pagemargin_L', Pos_P = '$posp', Pos_GZ = '$posgz', 
									Linemargin = '$linemargin', Schrift = '$schrift', Schriftsize = '$schriftsize', Textbreite = '$textbreite', Zeilenhoehe = '$zeilenhoehe', Seitenhoehe = '$seitenhoehe',
									VeryBig = '$verybigsize', H1Size = '$h1size', H2Size = '$h2size', H3Size = '$h3size', H1Height = '$h1hoehe', H2Height = '$h2hoehe', H3Height = '$h3hoehe'
									WHERE ID = 1" ;
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		}
?>

<?php include( "../php/head.php" ) ; ?>

		<div id="mains">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mains_oben">
					<div class="clear"></div>
					<div class="tr">
						<button type="submit" Name="save" class="okay_buttn"></button>
						<a href="../index.php?seite=admin" class="back_buttn"></a>
						<div class="etxt"><?php echo $errortxt ; ?></div>
						</div> <!-- tr -->	
					<div class="clear"></div>
					<sectn>
						<div class="tr">
							<div class="col_200">Seitenh&ouml;he:</div>
							<div class="col_80"><input type="number" name="page_H" class="col_60r" step="0.01" value="<?php echo $page_H ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Seitenbreite:</div>
							<div class="col_80"><input type="number" name="page_W" class="col_60r" step="0.01" value="<?php echo $page_W ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Deckblatt - Rand oben:</div>
							<div class="col_80"><input type="number" name="titlemargin_T" class="col_60r" step="0.01" value="<?php echo $titlemargin_T ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Deckblatt - Rand rechts:</div>
							<div class="col_80"><input type="number" name="titlemargin_R" class="col_60r" step="0.01" value="<?php echo $titlemargin_R ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Deckblatt - Rand unten:</div>
							<div class="col_80"><input type="number" name="titlemargin_B" class="col_60r" step="0.01" value="<?php echo $titlemargin_B ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Deckblatt - Rand links:</div>
							<div class="col_80"><input type="number" name="titlemargin_L" class="col_60r" step="0.01" value="<?php echo $titlemargin_L ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Position - gem. &sect;:</div>
							<div class="col_80"><input type="number" name="posp" class="col_60r" step="1" value="<?php echo $posp ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Position - GZ:</div>
							<div class="col_80"><input type="number" name="posgz" class="col_60r" step="1" value="<?php echo $posgz ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Deckblattbildbreite - links:</div>
							<div class="col_80"><input type="number" name="titlpicleft_W" class="col_60r" step="1" value="<?php echo $titlpicleft_W ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Deckblattbildbreite - rechts:</div>
							<div class="col_80"><input type="number" name="titlpicright_W" class="col_60r" step="1" value="<?php echo $titlpicright_W ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Seitenrand, oben:</div>
							<div class="col_80"><input type="number" name="pagemargin_T" class="col_60r" step="1" value="<?php echo $pagemargin_T ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Seitenrand, rechts:</div>
							<div class="col_80"><input type="number" name="pagemargin_R" class="col_60r" step="1" value="<?php echo $pagemargin_R ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Seitenrand, unten:</div>
							<div class="col_80"><input type="number" name="pagemargin_B" class="col_60r" step="1" value="<?php echo $pagemargin_B ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Seitenrand, links:</div>
							<div class="col_80"><input type="number" name="pagemargin_L" class="col_60r" step="1" value="<?php echo $pagemargin_L ; ?>"></div>
							<div class="col_unit">mm</div>
							</div>
						</sectn>
					<sectn>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Abstand - Linie/Text:</div>
							<div class="col_80"><input type="number" name="linemargin" class="col_60r" step="1" value="<?php echo $linemargin ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Schrift:</div>
							<div class="col_80"><input type="text" name="schrift" class="col_80" value="<?php echo $schrift ; ?>"></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Schriftgr&ouml;&szlig;e:</div>
							<div class="col_80"><input type="number" name="schriftsize" class="col_60r" step="1" value="<?php echo $schriftsize ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Gro&szlig;e Schrift:</div>
							<div class="col_80"><input type="number" name="verybigsize" class="col_60r" step="1" value="<?php echo $verybigsize ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Gr&ouml;&szlig;e &Uuml;berschrift 1:</div>
							<div class="col_80"><input type="number" name="h1size" class="col_60r" step="1" value="<?php echo $h1size ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Gr&ouml;&szlig;e &Uuml;berschrift 2:</div>
							<div class="col_80"><input type="number" name="h2size" class="col_60r" step="1" value="<?php echo $h2size ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Gr&ouml;&szlig;e &Uuml;berschrift 3:</div>
							<div class="col_80"><input type="number" name="h3size" class="col_60r" step="1" value="<?php echo $h3size ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">H&ouml;he &Uuml;berschrift 1:</div>
							<div class="col_80"><input type="number" name="h1hoehe" class="col_60r" step="1" value="<?php echo $h1hoehe ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">H&ouml;he &Uuml;berschrift 2:</div>
							<div class="col_80"><input type="number" name="h2hoehe" class="col_60r" step="1" value="<?php echo $h2hoehe ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">H&ouml;he &Uuml;berschrift 3:</div>
							<div class="col_80"><input type="number" name="h3hoehe" class="col_60r" step="1" value="<?php echo $h3hoehe ; ?>"></div>
							<div class="col_unit">px</div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Textbreite:</div>
							<div class="col_80"><input type="number" name="textbreite" class="col_60r" step="1" value="<?php echo $textbreite ; ?>"></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Zeilenh&ouml;he:</div>
							<div class="col_80"><input type="number" name="zeilenhoehe" class="col_60r" step="1" value="<?php echo $zeilenhoehe ; ?>"></div>
							</div>
						<div class="clear"></div>
						<div class="tr">
							<div class="col_200">Druckbereichs&ouml;he:</div>
							<div class="col_80"><input type="number" name="seitenhoehe" class="col_60r" step="1" value="<?php echo $seitenhoehe ; ?>"></div>
							</div>
						</sectn>
					</div> <!-- mains_oben -->
			</form>
		</div><!-- main -->
	</div>  <!-- container -->
</body>
</html>
<?php
} // if ok
?>
<!-- EOF -->