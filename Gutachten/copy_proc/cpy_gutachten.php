<?php
	session_start( ) ;

	include( '../../php/DBselect.php' ) ;
	include( '../../php/GlobalProject.php' ) ;
	include( 'insert_gutachten.php' ) ;

	unset( $_SESSION[ 'navi' ] ) ;
	$_SESSION[ 'navi' ] = 'copy' ;

	$oldgutachten = $_REQUEST[ 'gutachten' ] ;

	$abfrage = "SELECT * FROM $db_gutachten WHERE GutachtenID = $oldgutachten" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_gutachten.php' ) ;
		$gzold = $gz ;
		$indexold = $index ;
		$gz = $gz . ' - Kopie' ;
		$index = '' ;
		}

	if( isset( $_POST[ 'save' ] ) ) {
		$gz = $_POST[ 'gz' ] ;
		$index = $_POST[ 'index' ] ;
		insert_gutachten( $oldgutachten, $gz, $index ) ;
		$errortxt = 'Gutachten kopiert' ;
		}
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../../php/head.php" ) ; ?>

		<div id="mainstammd">
			<div class="tabrowhead">Gutachten kopieren</div>

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >

				<div class="b5tabrow">
					<div class="col_60mr"><?php echo '.' ; ?></div>
					<div class="col_190">GZ</div>
					<div class="col_60mr">Index</div>
					</div>

				<div class="b5tabrow">
					<div class="col_60mr">Alt</div>
					<div class="col_190mr"><?php echo $gzold ; ?></div>
					<div class="col_60mr"><?php echo $indexold ; ?></div>
					</div>

				<div class="b5tabrow">
					<div class="col_60mr">Neu</div>
					<input type="text" name="gz" class="col_190mr" value="<?php echo $gz ; ?>">
					<div class="col_60mr">
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
						</div>
					</div>

				<div class="b5tabrow">
					<button type="submit" name="save" class="okay_buttn"></button>
					<a href="../gutachten.php" class="back_buttn"></a>
					<div class="errmsg"><?php echo $errortxt ; ?></div>
					</div> <!-- buttnbox -->
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<!-- EOF -->