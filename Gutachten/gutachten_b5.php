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
	$_SESSION[ 'navi' ] = 'B.5' ;

	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$abfragex = "SELECT * FROM $db_gutachten WHERE GutachtenID = '$gutachtenid'" ;
	$ergebnisx = mysql_query( $abfragex ) OR die( "Error: $abfragex <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnisx ) ) {
		include( '../php/get_db_gutachten.php' ) ;
		}
	$head1		= 'Gutachten:' ;
	$head1txt	= $gz . ', ' . $str . ', ' . $plz . ' ' . $ort ;
	$head2		= '' ;
	$head2txt	= '' ;
	$head3		= '' ;
	$head3txt = '' ;
	$head4		= '' ;
	$head4txt = '' ;

	if( isset( $_POST[ 'delete' ] ) ) {
		$nr = $_POST[ 'delete' ] ;
		$frage = "DELETE FROM $db_ugsink WHERE ID = $nr" ;
		$reply = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'save1' ] ) ) {
		$txt = $_POST[ 'save1' ] ;
		$gesetzl = $_POST[ 'gesetz' ] ;
		$frage = "SELECT * FROM $db_ugsource WHERE Beschreibg = '$gesetzl' AND Gruppe = 1" ;
		$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $reply ) ;
		$txt = $row->Txt ;
		$frage = "UPDATE $db_gutachten SET GesetzlGrundl = '$gesetzl', Gutachten = '$txt' WHERE GutachtenID = $gutachtenid" ;
		$reply = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;

		$frage = "SELECT * FROM $db_ugsink WHERE Gruppe = 1" ;
		$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
		$row = mysql_fetch_object( $reply ) ;
		if( $row ) {
			$frage	= "UPDATE $db_ugsink SET Was = '$gesetzl' WHERE Gruppe = 1" ;
			}
		else {
			$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$gutachtenid', '1', '', '$gesetzl', '', '', '' )" ;
			}
		$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'save2' ] ) ) {
		$was = $_POST[ 'selection' ] ;
		$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
								VALUES( NULL, '$gutachtenid', '2', '', '$was', '', '', '' )" ;
		$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
		}
	elseif( isset( $_POST[ 'save3' ] ) ) {
		$wann = $_POST[ 'wann3' ] ;
		$was	= $_POST[ 'was3' ] ;
		if( $wann != '' AND $was != '' ) {
			$errortxt = '' ;
			$wer	= $_POST[ 'wer3' ] ;
			$wo		= $_POST[ 'wo3' ] ;
			$akte	= $_POST[ 'akte3' ] ;
			$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$gutachtenid', '3', '$wann', '$was', '$wer', '$wo', '$akte' )" ;
			$reply = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
			}
		else {
			$errortxt = 'Datum und WAS ausf&uuml;llen' ;
			}
		}
	elseif( isset( $_POST[ 'save4' ] ) ) {
		$wann = $_POST[ 'wann4' ] ;
		$was	= $_POST[ 'was4' ] ;
		if( $wann != '' AND $was != '' ) {
			$errortxt = '' ;
			$wer	= $_POST[ 'wer4' ] ;
			$wo		= $_POST[ 'wo4' ] ;
			$akte	= $_POST[ 'akte4' ] ;
			$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$gutachtenid', '4', '$wann', '$was', '$wer', '$wo', '$akte' )" ;
			$reply = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
			}
		else {
			$errortxt = 'Datum und WAS ausf&uuml;llen' ;
			}
		}
	elseif( isset( $_POST[ 'save5' ] ) ) {
		$wann = $_POST[ 'wann5' ] ;
		$was	= $_POST[ 'was5' ] ;
		if( $wann != '' AND $was != '' ) {
			$errortxt = '' ;
			$wer	= $_POST[ 'wer5' ] ;
			$wo		= $_POST[ 'wo5' ] ;
			$akte	= $_POST[ 'akte5' ] ;
			$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$gutachtenid', '5', '$wann', '$was', '$wer', '$wo', '$akte' )" ;
			$reply = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
			}
		else {
			$errortxt = 'Datum und WAS ausf&uuml;llen' ;
			}
		}
	elseif( isset( $_POST[ 'save6' ] ) ) {
		$wann = $_POST[ 'wann6' ] ;
		$was	= $_POST[ 'was6' ] ;
		if( $wann != '' AND $was != '' ) {
			$errortxt = '' ;
			$wer	= $_POST[ 'wer6' ] ;
			$wo		= $_POST[ 'wo6' ] ;
			$akte	= $_POST[ 'akte6' ] ;
			$frage	= "INSERT INTO $db_ugsink (`ID`, `Gut8en`, `Gruppe`, `Wann`, `Was`, `Wer`, `Wo`, `Akte` ) 
									VALUES( NULL, '$gutachtenid', '6', '$wann', '$was', '$wer', '$wo', '$akte' )" ;
			$reply = mysql_query( $frage ) OR die( "Error: $frage <br>". mysql_error() ) ;
			}
		else {
			$errortxt = 'Datum und WAS ausf&uuml;llen' ;
			}
		}
	elseif( isset( $_POST[ 'save7' ] ) ) {
		$nichtbefund= $_POST[ 'nichtbefund' ] ;
		$frage = "UPDATE $db_gutachten SET NichtBefundet = '$nichtbefund' WHERE GutachtenID = $gutachtenid" ;
		$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
		}
	else {
		}

//	include( 'Subroutines/make_liste_grundlagen.php' ) ;

	$liste = array( ) ;
	$gutachtenid = $_SESSION[ 'gutachtenid' ] ;

	$frage = "SELECT * FROM $db_ugsink WHERE Gut8en = $gutachtenid ORDER BY Gruppe, Was" ;
	$reply = mysql_query( $frage )  OR die( "Error: $frage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $reply ) ) {
		$id			= $row->ID ;
		$gruppe	= $row->Gruppe ;
		$gut8en	= $row->Gut8en ;
		$what		= $row->Empfehlung ;
		$when		= $row->Wann ;
		$what		= $row->Was ;
		$who		= $row->Wer ;
		$where	= $row->Wo ;
		$akt		= $row->Akte ;
		if( $when		== '0000-00-00' ) { $when		= '-' ; } else { $when = date( "Y.m.d", strtotime( $when ) ) ; }
		if( $what		== '' ) { $what		= '-' ; }
		if( $who		== '' ) { $who		= '-' ; }
		if( $where	== '' ) { $where	= '-' ; }
		if( $akt 		== '' ) { $akt		= '-' ; }

		switch( $gruppe ) {
			case 1 :
				$grp = 'B 5.1 Gesetzliche Grundlagen' ;
				break ;
			case 2 :
				$grp = 'B 5.2 Empfehlungen' ;
				break ;
			case 3 :
				$grp = 'B 5.3 Baubeh&ouml;rdlicher Konsens' ;
				break ;
			case 4 :
				$grp = 'B 5.4 Vertr&auml;ge, Vereinbarungen' ;
				break ;
			case 5 :
				$grp = 'B 5.5 Gutachten, Entscheide' ;
				break ;
			case 6 :
				$grp = 'B 5.6 Sonstige Unterlagen' ;
				break ;
			default:
				$grp = 'B 5.7 Nicht befundet' ;
				$frage1 = "SELECT NichtBefundet FROM $db_gutachten WHERE GutachtenID = $gutachtenid" ;
				$reply1 = mysql_query( $frage1 )  OR die( "Error: $frage1 <br>". mysql_error() ) ;
				$row1 = mysql_fetch_object( $reply1 ) ;
				$nb	= $row1->NichtBefundet ;
				$liste [ ] = array( 'grp'=>'B 4.7', 'id'=>$id, 'gut8en'=>$gutachtenid, 'when'=>'', 'what'=>$nb, 'where'=>'', 'who'=>'', 'akt'=>'' ) ;
				break ;
			}  // switch $gruppe

		$liste [ ] = array( 'grpp'=>$gruppe, 'grp'=>$grp, 'id'=>$id, 'gut8en'=>$gut8en, 'when'=>$when, 'what'=>$what, 'where'=>$where, 'who'=>$who, 'akt'=>$akt ) ;
		}
//--------------------------------------------------------------------
	include( "../php/head.php" ) ; 
	?>

		<div class="clear"></div>
		<div id="mainb5">

			<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >
				<div id="mainb5_oben">
					<?php
					if( !$abgeschl ) {
						?>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.1 Gesetzliche Grundlagen</div>
						<div class="col1a">
							<?php
							$abf1 = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 1 ORDER BY Beschreibg" ;
							$erg1 = mysql_query( $abf1 ) OR die( "Error: $abf1 <br>". mysql_error() ) ;
							echo '<select name="gesetz" size="1" class="col1">'; 
							while( $row = mysql_fetch_object( $erg1 ) ) {
								$gesetz = $row->Beschreibg ;
								$txt = $row->Txt ;
								if( $gesetz == $gesetzl ) { 	echo '<option selected value="' . $gesetz . '">' . $gesetz . '</option>'; }
								else { 	echo '<option value="' . $gesetz . '">' . $gesetz . '</option>'; }
								} //while - gesetzl Grundlage
							echo'</select>';
							?>
							</div>
						<div class="col_buttons1"><button type="submit" name="save1" class="okay_buttn" value="<?php echo $txt ; ?>"></button></div>
						</div>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.2 Empfehlungen</div>
						<div class="col1a">
							<?php
							$abf2 = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 2 ORDER BY Beschreibg" ;
							$erg2 = mysql_query( $abf2 ) OR die( "Error: $abf2 <br>". mysql_error() ) ;
							echo '<select name="selection" size="1" class="col1">'; 
							while( $row = mysql_fetch_object( $erg2 ) ) {
								$selection = $row->Beschreibg ;
								if( $selection == $empfehlgen ) { 	echo '<option selected value="' . $selection . '">' . $selection . '</option>'; }
								else { 	echo '<option value="' . $selection . '">' . $selection . '</option>'; }
								} //while - Empfehlung
							echo'</select>';
							?>
							</div>
						<div class="col_buttons1"><button type="submit" Name="save2" class="okay_buttn"></button></div>
						</div>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.3 Baubeh&ouml;rdlicher Konsens</div>
						<div class="col0"><input type="date" name="wann3" class="col0" value="<?php echo $wann3 ?>"></div>
						<div class="col1">
							<?php
							$abfxx = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 3 ORDER BY Beschreibg" ;
							$ergxx = mysql_query( $abfxx ) OR die( "Error: $abfxx <br>". mysql_error() ) ;
							echo '<select name="was3" size="1" class="col1">'; 
							while( $row = mysql_fetch_object( $ergxx ) ) {
								$wahl = $row->Beschreibg ;
								if( $wahl == $was3 ) { 	echo '<option selected value="' . $wahl . '">' . $wahl . '</option>'; }
								else { 	echo '<option value="' . $wahl . '">' . $wahl . '</option>'; }
								} //while
							echo'</select>';
							?>
							</div>
						<div class="col2"><input type="text" name="wer3" maxlength="30" class="col2" value="<?php echo $wer3 ; ?>"></div>
						<div class="col2"><input type="text" name="wo3" maxlength="30" class="col2" value="<?php echo $wo3 ; ?>"></div>
						<div class="col2"><input type="text" name="akte3" maxlength="30" class="col2" value="<?php echo $akte3 ; ?>"></div>
						<div class="col3"><button type="submit" Name="save3" class="okay_buttn"></button></div>
						</div>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.4 Vertr&auml;ge, Vereinbarungen</div>
						<div class="col0"><input type="date" name="wann4" class="col0" value="<?php echo $wann4 ?>"></div>
						<div class="col1">
							<?php
							$abfxx = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 4 ORDER BY Beschreibg" ;
							$ergxx = mysql_query( $abfxx ) OR die( "Error: $abfxx <br>". mysql_error() ) ;
							echo '<select name="was4" size="1" class="col1">'; 
							while( $row = mysql_fetch_object( $ergxx ) ) {
								$wahl = $row->Beschreibg;
								if( $wahl == $was4 ) { 	echo '<option selected value="' . $wahl . '">' . $wahl . '</option>'; }
								else { 	echo '<option value="' . $wahl . '">' . $wahl . '</option>'; }
								} //while 
							echo'</select>';
							?>
							</div>
						<div class="col2"><input type="text" name="wer4" maxlength="30" class="col2" value="<?php echo $wer4 ; ?>"></div>
						<div class="col2"><input type="text" name="wo4" maxlength="30" class="col2" value="<?php echo $wo4 ; ?>"></div>
						<div class="col2"><input type="text" name="akte4" maxlength="30" class="col2" value="<?php echo $akte4 ; ?>"></div>
						<div class="col3"><button type="submit" Name="save4" class="okay_buttn"></button></div>
						</div>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.5 Gutachten, Entscheide</div>
						<div class="col0"><input type="date" name="wann5" class="col0" value="<?php echo $wann5 ?>"></div>
						<div class="col1mr">
							<?php
							$abfxx = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 5 ORDER BY Beschreibg" ;
							$ergxx = mysql_query( $abfxx ) OR die( "Error: $abfxx <br>". mysql_error() ) ;
							echo '<select name="was5" size="1" class="col1">'; 
							while( $row = mysql_fetch_object( $ergxx ) ) {
								$wahl = $row->Beschreibg ;
								if( $wahl == $was5 ) { 	echo '<option selected value="' . $wahl . '">' . $wahl . '</option>'; }
								else { 	echo '<option value="' . $wahl . '">' . $wahl . '</option>'; }
								} //while
							echo'</select>';
							?>
							</div>
						<div class="col2"><input type="text" name="wer5" maxlength="30" class="col2" value="<?php echo $wer5 ; ?>"></div>
						<div class="col2"><input type="text" name="wo5" maxlength="30" class="col2" value="<?php echo $wo5 ; ?>"></div>
						<div class="col2"><input type="text" name="akte5" maxlength="30" class="col2" value="<?php echo $akte5 ; ?>"></div>
						<div class="col3"><button type="submit" Name="save5" class="okay_buttn"></button></div>
						</div>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.6 Sonstige Unterlagen</div>
						<div class="col0"><input type="date" name="wann6" class="col0" value="<?php echo $wann6 ?>"></div>
						<div class="col1">
							<?php
							$abfxx = "SELECT * FROM $db_ugsource WHERE Aktiv AND Gruppe = 6 ORDER BY Beschreibg" ;
							$ergxx = mysql_query( $abfxx ) OR die( "Error: $abfxx <br>". mysql_error() ) ;
							echo '<select name="was6" size="1" class="col1">'; 
							while( $row = mysql_fetch_object( $ergxx ) ) {
								$wahl = $row->Beschreibg ;
								if( $wahl == $was6 ) { 	echo '<option selected value="' . $wahl . '">' . $wahl . '</option>'; }
								else { 	echo '<option value="' . $wahl . '">' . $wahl . '</option>'; }
								} //while - gesetzl Grundlage
							echo'</select>';
							?>
							</div>
						<div class="col2"><input type="text" name="wer6" maxlength="30" class="col2" value="<?php echo $wer6 ; ?>"></div>
						<div class="col2"><input type="text" name="wo6" maxlength="30" class="col2" value="<?php echo $wo6 ; ?>"></div>
						<div class="col2"><input type="text" name="akte6" maxlength="30" class="col2" value="<?php echo $akte6 ; ?>"></div>
						<div class="col3"><button type="submit" Name="save6" class="okay_buttn"></button></div>
						</div>

					<div class="clear"></div>
					<div class="trin">
						<div class="col1bold">B 5.7 Nicht befundet</div>
						<div><textarea name="nichtbefund" maxlength="64000" cols="225" rows="5" class="colnix" ><?php echo $nichtbefund ; ?></textarea></div>
						<div class="col3"><button type="submit" Name="save7" class="okay_buttn"></button></div>
						</div>

					<?php
					}  // if( !$abgeschl )					<div class="col_190mr">Gruppe</div>
					?>

					<div class="clear"></div>
					<div class="trhd">
						<div class="col1b">Grundlage / Unterlage</div>
						<div class="col0">Datum</div>
						<div class="col1">Dokument</div>
						<div class="col2">Erstellt von</div>
						<div class="col2">F&uuml;r / mit / an</div>
						<div class="col2">Akte / GZ</div>
						</div>

					</div> <!-- mainb5_oben -->

				<div id="mainb5_unten">
					<?php
					$merkgrp = '' ;
					$i = 0 ;
					$anz = count( $liste ) ;
					while( $i < $anz ) {
						$grpp		= $liste [ $i ][ 'grpp' ] ;
						$grp		= $liste [ $i ][ 'grp' ] ;
						$id			= $liste [ $i ][ 'id' ] ;
						$gut8en	= $liste [ $i ][ 'gut8en' ] ;
						$when		= $liste [ $i ][ 'when' ] ;
						$what		= $liste [ $i ][ 'what' ] ;
						$who		= $liste [ $i ][ 'who' ] ;
						$where	= $liste [ $i ][ 'where' ] ;
						$akt		= $liste [ $i ][ 'akt' ] ;
						?>

						<div class="tr">
							<?php
							if( $merkgrp == $grp ) { $grp = '-' ;} else { $merkgrp = $grp ; }
							?>
							<div class="col1b"><?php echo $grp ; ?></div>
							<div class="col0"><?php echo $when ; ?></div>
							<div><textarea disabled maxlength="64000" cols="20" rows="3" class="colwhat" ><?php echo $what ; ?></textarea></div>
							<div class="col2"><?php echo $who ; ?></div>
							<div class="col2"><?php echo $where ; ?></div>
							<div class="col2"><?php echo $akt ; ?></div>
							<?php
							if( !$abgeschl AND $grpp > 1 ) {
								?>
								<div class="col3"><button type="submit" name="delete" class="delete_buttn" value="<?php echo $id ; ?>"></button></div>
								<?php
								}
								?>
						</div>
						<?php
						$i++ ;
						} // while
					if( $nichtbefund != '' ) {
						?>
						<div class="tr">
							<div class="col1b">B 5.7 Nicht befundet</div>
							<div><textarea disabled maxlength="64000" cols="122" rows="5" class="colnix" ><?php echo $nichtbefund ; ?></textarea></div>
							</div>
						<?php
						}
						?>
					</div> <!-- mainb5_unten -->

				</form>
			</div><!-- main -->
		</div>  <!-- container -->
	</body>
	</html>
<?php
	} // if ok
?>
<!-- EOF -->