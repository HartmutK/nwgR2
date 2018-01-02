<?php
function zuschlag_zubehoer( $zuabwohn ) {

	if( $zuabwohn > 0 ) {
		$table_zuab = array( ) ;
		$abf2 = "SELECT * FROM WohnungZuschlag WHERE InWohnung = $zuabwohn ORDER BY Zubehoer, GanzUnten, Bezeichnung" ;
		$erg2 = mysql_query( $abf2 )  OR die( "Error: $abf2 <br>". mysql_error() ) ;
		while( $rw2 = mysql_fetch_object( $erg2 ) ) {
			$zubbez	= $rw2->Bezeichnung ;
			$lge		= $rw2->Lage ;
			$flche	= $rw2->Flaeche ;
			$zub		= $rw2->Zubehoer ;
			$rpfl		= $rw2->Rechtspfleger ;
			if( $rpfl != '' ) {
				$zubbez = $rpfl ;
				}
			else {
				if( strpos( $zubbez, 'Terrasse' ) OR strpos( $zubbez, 'Balkon' )  ) {
					$zubbez = 'Terrasse/Balkon' ;
					}
				}
			$table_zuab [ ] = array( 'zubbez'=>$zubbez, 'lge'=>$lge, 'flche'=>$flche, 'zub'=>$zub ) ;
			} // while Zuschlag

		$j = 0 ;
		$cnt = count( $table_zuab ) ;
		$merkzub = -1 ;
		while( $j < $cnt ) {
			$idw1	= $table_zuab [ $j ][ 'idw' ] ;
			if( $idw == $idw1 ) {
				$zubbez	= $table_zuab [ $j ][ 'zubbez' ] ;
				$lge		= $table_zuab [ $j ][ 'lge' ] ;
				$flch		= $table_zuab [ $j ][ 'flche' ] ;
				$zub		= $table_zuab [ $j ][ 'zub' ] ;
				if( $lge == '' ) { $lge = '-' ; }
				if( $merkzub != $zub ) {
					if( $zub ) {
						$kpftxt = 'Zubeh&ouml;r' ;
						}
					else {
						$kpftxt = 'Zuschlag' ;
						} // zub
					?>
					<div class="clear"></div>
					<div class="tr">
						<div class="colbold"><?php echo $kpftxt ; ?></div>
						</div>
					<?php
					$merkzub = $zub ;
					$gs = 0 ;
					} // !=
				?>
				<div class="clear"></div>
				<div class="tr">
					<div class="sp1l"><?php echo $zubbez ; ?></div>
					<div class="sp3"><?php echo $lge ; ?></div>
					<div class="sp7_2"><?php echo $flch ; ?></div>
					<?php
					$gs = $gs + $flch ;
					if( $merkzub != $table_zuab [ $j + 1 ][ 'zub' ] ) {
						$gs = number_format( round( $gs, 2 ), 2, ',', '.' ) ;
						?>
						<div class="sp7"><?php echo $gs ; ?></div>
						<?php
						}
						?>
					</div>
				<?php
				} // ==
			$j++ ;
			}
		}
	}
?>
<!-- EOF -->