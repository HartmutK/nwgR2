<?php
session_start( ) ;

include( '../../php/DBselect.php' ) ;
?>
<style>
	.laengrow {
		clear: both ;
		float: left ;
		width: 100% ;
		padding-left: 10px ;
		}

	.t10,.t75 , .t125 , .t400 {
		float: left ;
		}
	.t10 {
		width: 10px ;
		padding-left: 5px ;
		}
	.t75 {
		width: 75px ;
		}
	.t125 {
		width: 125px ;
		}
	.t400 {
		width: 350px ;
		}
	.save_buttn {
		float: left ;
		cursor: pointer ;
		border: none ;
		margin: 25px ;
		background-repeat: no-repeat ;
		width: 25px ;
		height: 24px ;
		}
	</style>

<?php
$zuabschlag = $_REQUEST[ 'was' ] ;

$abfrage = "SELECT * FROM $db_zuabschlag WHERE ZuAbID = $zuabschlag" ;
$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
while( $row = mysql_fetch_object( $ergebnis ) ) {
	include( '../../php/get_db_zuabschlag.php' ) ;
	?>
	<form method="post" Name='input_fields' class="login" enctype="multipart/form-data" >

		<div class="laengrow">
			<div class="t125">K&uuml;rzel:</div>
			<div class="td25"><?php echo $zuab_kurz ; ?></div>
			</div>
		<div class="laengrow">
			<div class="t125">Minimum:</div>
			<div class="td25"><?php echo $zuab_min ; ?></div>
			</div>
		<div class="laengrow">
			<div class="t125">Wert:</div>
			<div class="t75"><input type="number" name="wieviel" class="t75" step="0.1" value="<?php echo $zuab_wieviel ; ?>"></div>
			<div class="t10"><?php echo $einheit ; ?></div>
			</div>
		<div class="laengrow">
			<div class="t125">Maximum:</div>
			<div class="td25"><?php echo $zuab_max ; ?></div>
			</div>
		<div class="laengrow">
			<div class="t125">Beschreibung:</div>
			<div class="t400"><?php echo $zuab_komment ; ?></div>
			</div>

		<button type="submit" name="save" class="save_buttn"><?php echo '<img src="../../CSS/buttons/save.png">' ; ?></button>

		</form>

	<?php
	if( isset( $_POST[ 'save' ] ) ) {
		$gutachtenid	= $_SESSION[ 'gutachtenid' ] ;
		$zuab_wieviel	= $_POST[ 'wieviel' ] ;
		$abfrage = "SELECT ZuAbID FROM $db_zuabgutacht WHERE GutachtenID = $gutachtenid AND Kuerzel = '$zuab_kurz'" ;
		$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
		if( $row = mysql_fetch_object( $ergebnis ) ) {
			$zuabid = $row->ZuAbID ;
			$abfrage = "UPDATE $db_zuabgutacht SET Wieviel = '$zuab_wieviel' WHERE ZuAbID = $zuabid" ;
			}
		else {
			$abfrage	= "INSERT INTO $db_zuabgutacht (`ZuAbID`, `GutachtenID`, `Kuerzel`, `Min`, `Wieviel`, `Max`, `Einheit`, `Kommentar` ) 
										VALUES( NULL, '$gutachtenid', '$zuab_kurz', '$zuab_min', '$zuab_wieviel', '$zuab_max', '$einheit', '$zuab_komment' )" ;
			}
		$ergebnis = mysql_query( $abfrage ) OR die( "Error: $abfrage <br>". mysql_error() ) ;
		}
	}
?>
<!-- EOF -->