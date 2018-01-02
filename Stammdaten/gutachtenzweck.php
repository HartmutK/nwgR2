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
	$_SESSION[ 'navi' ] = 'Stammdaten' ;

	$topic = 'Gutachtenzweck' ;
	$errortxt = '' ;
	$anabtxt = 'Abmelden' ;

	if( isset( $_POST[ 'save' ] ) ) {
		$zweck  = $_POST[ 'zweck' ] ;

		if( $zweck <> '' ) {
			if( isset( $_SESSION[ 'merkid' ] ) ) {
				$tableid = $_SESSION[ 'tableid' ] ;
				$abfrage = "UPDATE $db_gut8enzweck SET Zweck = '$zweck' WHERE ZweckID = '$tableid'" ;
				unset( $_SESSION['merkid'] ) ;
				$errortxt = $topic . ' ge&auml;ndert' ;
				}
			else {
				$abfrage	= "INSERT INTO $db_gut8enzweck (`ZweckID`, `Zweck` ) VALUES( NULL, '$zweck' )" ;
				$errortxt = $topic . ' gespeichert' ;
				}
			$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
			unset( $_POST['save'] ) ;
			$zweck = '' ;
			}
		$chnge = false ;
		}

	if( isset( $_POST[ 'detail' ] ) ) {
		$tableid = $_POST[ 'detail' ] ;
		$abfrage = "SELECT * FROM $db_gut8enzweck WHERE ZweckID = '$tableid'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $ergebnis ) ;
		$zweck = $row->Zweck ;
		$_SESSION[ 'merkid' ] = $row->ZweckID ;
		$_SESSION[ 'tableid' ] = $tableid ;
		$chnge = true ;
		}

	if( isset( $_POST[ 'del' ] ) ) {
		$tableid = $_POST[ 'del' ] ;
		$abf = "DELETE FROM $db_gut8enzweck WHERE ZweckID = '$tableid'" ;
		$erg = mysql_query( $abf ) OR die( "Error: $abf <br>". mysql_error() ) ;
		$errortxt = $topic . ' gel&ouml;scht' ;
		unset( $_POST['delete'] ) ;
		$zweck = '' ;
		}

	if( isset( $_POST[ 'back' ] ) ) {
		unset( $_POST['back'] ) ;
		$chnge = false ;
		}

	unset( $_SESSION[ 'liste' ] ) ;
	$_SESSION[ 'liste' ] = array() ;
	$liste = array( ) ;
	$abfrage = "SELECT * FROM $db_gut8enzweck ORDER BY Zweck" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		$zwckid = $row->ZweckID ;
		$zwck	 = $row->Zweck ;
		$liste [ ] = array( 'zwckid'=>$zwckid, 'zwck'=>$zwck ) ;
		}
	$_SESSION[ 'liste' ] = $liste ;
	?>

<!------------------------------------------------------------------------------------------>

<?php include( "../php/head.php" ) ; ?>

		<div id="mainstammd">
			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div class="b5tabrow">
					<div class="col_600mr"><textarea name="zweck" maxlength="64000" cols="122" rows="5" class="col_600" ><?php echo $zweck ; ?></textarea></div>
					<div class="col_40mr">
						<button type="submit" Name="save" class="okay_buttn"></button>
						<?php
						if( $chnge ) {
							?>
							<button type="submit" Name="back" class="back_buttn"></button>
							<?php
							}
						else {
							?>
							<a href="../index.php?seite=admin" class="back_buttn"></a>
							<?php
							}
							?>
						</div>
					<div class="errmsg"><?php echo $errortxt ; ?></div>
					</div>

				<div class="b5tabrowhd">
					<div class="col_190mr">Gutachtenzweck</div>
					</div>

				<?php
				$i = 0 ;
				$liste = $_SESSION[ 'liste' ] ;
				$anz_liste = count( $liste ) ;

				while( $i < $anz_liste ) {
					$zwckid	= $liste [ $i ][ 'zwckid' ] ;
					$zwck		= $liste [ $i ][ 'zwck' ] ;
					?>
					<div class="b5tabrow">
						<div class="col_600mr"><?php echo $zwck ; ?></div>
						<div class="col_85mr">
							<button type="submit" name="detail" class="edit_buttn" value="<?php echo $zwckid ; ?>"></button>
							<button type="submit" name="del" class="delete_buttn" value="<?php echo $zwckid ; ?>"></button>
							</div>
						</div>
					<?php
					$i++ ;
					}
					?>
				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->