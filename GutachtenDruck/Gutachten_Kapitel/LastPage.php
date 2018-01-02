<?php
unset( $_SESSION[ 'nixfoot' ] ) ;
$_SESSION[ 'nixfoot' ] = true ;

$pic_h = $page_H - $titlemargin_T - $titlemargin_B ;

$pdf->Image( '../Bilder/Baukult_Gutachten2.png', $titlemargin_R, $titlemargin_T, $titlpicright_W, $pic_h, 'png' ) ;

$nixhead = false ;
$nixfoot = false ;
?>
<!-- EOF -->
