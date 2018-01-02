<?php
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
	
	$dateform			= "Y.m.d" ;

	class PDF extends PDF_Rotate {

//		protected $_toc=array() ;
		protected $_numbering=true ;
		protected $_numPageNum=1 ;

		function AddPage( $orientation='', $format='', $rotation=0 ) {
			parent::AddPage( $orientation, $format, $rotation ) ;
			if( $this->_numbering )
				$this->_numPageNum++ ;
			}

		function startPageNums() {
			$this->_numbering=true ;
			}

		function stopPageNums() {
			$this->_numbering=false ;
			}

		function numPageNo() {
			return $this->_numPageNum ;
			}

    function TOC_Entry($txt, $level=0) {
 	  	global $_toc ;
			$_toc[] = array( 't'=>$txt, 'l'=>$level, 'p'=>$this->numPageNo() );
			}

		function Header() {
			global $schrift, $schriftsize, $textbreite, $kopftext, $nixhead, $vorabzug, $paragr6 ;

			if( $nixhead OR $paragr6 ) {
				return ;
				}

			//Put the watermark
			if( $vorabzug ) {
				$this->RotatedImage( '../Bilder/baukult-vorabzug.jpg', 20, 105, 150, 83, 1 ) ;
				}

			// Titel 
			$tl = $textbreite ;
			if( $vorabzug ) {
				$this->SetFont( $schrift, 'B', $schriftsize ) ;
				$this->SetTextColor( 60, 155, 250 ) ;
				$this->Cell( 22, 5, 'VORABZUG: ', 'B', 0, 'L' ) ;
				$this->SetTextColor( 0, 0, 0 ) ;
				$tl = $textbreite - 22 ;
				}
			$this->SetFont( $schrift, '', $schriftsize ) ;
			$this->Cell( $tl, 5, $kopftext, 'B', 1, 'L' ) ;
			// Zeilenumbruch
			$this->SetFont( $schrift, '', $schriftsize ) ;
			$this->Ln(2) ;
			}  // End Header


		function Footer() {
			global $schrift, $schriftsize, $textbreite, $pagemargin_B, $zeilenhoehe, $nixfoot, $paragr6, $fusstext, $nixnb ;

			if( isset( $_SESSION[ 'nixfoot' ] ) OR $paragr6 ) {
				unset( $_SESSION[ 'nixfoot' ] ) ;
				return ;
				}

			// Set Marginbottom
			$this->SetY( -1 * $pagemargin_B ) ;
			$this->SetFont( $schrift, '', $schriftsize ) ;
			$this->Cell( $textbreite, 0, '', 'B', 1, 'L' ) ;

			$pagenr = $this->PageNo() ;

			unset( $_SESSION[ 'nb' ] ) ;
			$_SESSION[ 'nb' ] = $pagenr ;

			if( $nixnb ) {
				$dat = date( 'd.m.Y' ) ;
				$this->Cell( 140, 5, $dat, '', 0, 'L') ;
				$this->Cell( $textbreite-139, $zeilenhoehe, 'Seite ' . $pagenr, '', 0, 'R') ;
				}
			else {
				$this->Cell( 140, 5, $fusstext, '', 0, 'L') ;
				$this->Cell( $textbreite-136, $zeilenhoehe, 'Seite ' . $pagenr . ' von {nb} ', '', 0, 'R') ;
				}
			$this->SetFont( $schrift, '', $schriftsize ) ;
			}  // Footer


		function RotatedImage($file,$x,$y,$w,$h,$angle) {
			//Image rotated around its upper-left corner
			$this->Rotate($angle,$x,$y);
			$this->Image($file,$x,$y,$w,$h);
			$this->Rotate(0);
			}
		}
?>
<!-- EOF -->