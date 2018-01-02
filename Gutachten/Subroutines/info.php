<?php
session_start( ) ;

include( '../../php/DBselect.php' ) ;
?>
<style>
	#breite {
		width: 850px ;
		}
	.laengrow, .tabhead {
		clear: both ;
		float: left ;
		width: 730px ;
		padding-left: 10px ;
		}
	.tabhead {
		background-color: #7ea0b1 ;
		padding-top: 6px ;
		color:#FFFFFF ;
		margin: 10px 0 10px 0 ;
		vertical-align: middle ;
		height: 25px ;
		}

	.t10, .t45, .t100, .t145, .t400, .t50r {
		float: left ;
		}
	.t10 {
		width: 10px ;
		padding-left: 5px ;		
		}
	.t45 {
		width: 50px ;
		}
	.t100 {
		width: 100px ;
		}
	.t145 {
		width: 50px ;
		padding-left: 100px ;
		}
	.t400 {
		width: 400px ;
		padding-left: 20px ;		
		}
	.t50r {
		width: 50px ;
		text-align:right
		}
	</style>


<div id="breite">

	<div class="tabhead">
		<div class="t100">Gruppe</div>
		<div class="t45">K&uuml;rzel</div>
		<div class="t400">Beschreibung</div>
		<div class="t50r">Min</div>
		<div class="t50r">Wert</div>
		<div class="t50r">Max</div>
		</div>

	<?php
	$merkgrp = '' ;
	$abfrage = "SELECT * FROM $db_zuabschlag WHERE Aktiv ORDER BY Gruppe, Kuerzel" ;
	$ergebnis = mysql_query( $abfrage )  OR die( "Error: $abfrage <br>". mysql_error() ) ;
	while( $row = mysql_fetch_object( $ergebnis ) ) {
		include( '../../php/get_db_zuabschlag.php' ) ;
		?>
		<div class="laengrow">
		<?php
		if( $merkgrp == $zuab_gruppe ) {
			$grp = '' ;
			?>
			<div class="t145"><?php echo $zuab_kurz ; ?></div>
			<?php
			}
		else {
			$grp =  $zuab_gruppe ;
			?>
			<div class="t100"><?php echo $grp ; ?></div>
			<div class="t45"><?php echo $zuab_kurz ; ?></div>
			<?php
			}
		?>
			<div class="t400"><?php echo $zuab_komment ; ?></div>
			<div class="t50r"><?php echo $zuab_min ; ?></div>
			<div class="t50r"><?php echo $zuab_wieviel ; ?></div>
			<div class="t50r"><?php echo $zuab_max ; ?></div>
<!--			<div class="t10"><?php echo $einheit ; ?></div> -->
			</div>
		<?php
		$merkgrp = $zuab_gruppe ;
		}
	?>
	</div>
<!-- EOF -->