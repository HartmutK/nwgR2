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

			$frage = "TRUNCATE TABLE $db_importbim" ;
			mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

			$abfrage = "LOAD DATA LOCAL INFILE '$pfad' INTO TABLE ImportData COLUMNS TERMINATED BY ';' LINES TERMINATED BY '\n'
									( @field1, @field2, @field3, @field4, @field5, @field6, @field7, @field8 )
										SET RevitID = @field1, Zuordnung = @field2, Top = @field3, Name = @field4, Ebene = @field5, Belegung = @field6, Flche = REPLACE( @field7, ',', '.' ), SSR = @field8";
			mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;

			echo 'Importiert<br>' ;


			$abf1 = "SELECT * FROM $db_importbim" ;
			$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
			while( $rw1 = mysql_fetch_object( $erg1 ) ) {
				$id	= $rw1->ID ;

				$beleg	= $rw1->Belegung ;
				echo '<br>' . $beleg . '<br>' ;

//				$beleg = REPLACE( $beleg, "Ã¼", "ü" ) ;

				$needle = 'K' ;
				$pos = strpos( $beleg, $needle ) ;
				if( $pos > -1 ) echo 'K - ' . $needle . ' auf ' . $pos . '<br>' ;
				$needle = 'ü' ;
				$pos = strpos( $beleg, $needle ) ;
				if( $pos > -1 ) echo 'ü - ' . $needle . ' auf ' . $pos . '<br>' ;
				$needle = "Ã¼" ;
				$pos = strpos( $beleg, $needle ) ;
				$beleg = str_replace( "Ăź", iconv('UTF-8', 'windows-1252', 'ü' ), $beleg ) ;
				echo $beleg . '<br>' ;
				if( $pos > -1 ) echo 'Az - ' . $needle . ' auf ' . $pos . '<br>' ;
				$needle = 'ch' ;
				$pos = strpos( $beleg, $needle ) ;
				if( $pos > -1 ) echo $needle . ' auf ' . $pos . '<br>' ;
				}



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