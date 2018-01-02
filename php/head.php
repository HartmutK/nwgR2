<?php

if( isset( $_SESSION[ 'navi' ] ) ) {
	$navi = $_SESSION[ 'navi' ] ;
	}
else {
	$navi = 'index' ;
	$_SESSION[ 'navi' ] = $navi ;
	}

$d = substr( $navi, 0, 1 ) ;

if( $d == 'i' ) {
	$pfad = '' ;
	}
elseif( $d == '1' OR $d == '2' OR $d == '3' OR $d == 'A' OR $d == 'B' OR $d == 'C' OR $d == 'D' OR $d == 'E' OR $d == 'S' ) {
	$pfad = '../' ;
	}
else {
	$pfad = '../../' ;
	}

$A	= 'A Allgemeine Angaben' ;
$A1	= 'A.1 AuftraggeberInnen' ;
$A2	= 'A.2 AuftragnehmerInnen' ;
$A3	= 'A.3 Zweck des Gutachtens' ;
$A4	= 'A.4 Bewertungsstichtag' ;

$B	= 'B Befund' ;
$B1	= 'B.1 Grundbuchstand' ;
$B2	= 'B.2 Lage / Objekte / Ausstattung' ;
$B3	= 'B.3 Ortsbesichtigungen' ;
$B4	= 'B.4 Nutzfl&auml;chenberechnung' ;
$B5	= 'B.5 Grundlagen / Unterlagen' ;
$B6	= 'B.6 Plangrundlagen' ;

$B2_1	= 'B.2.1 Lage' ;
$B2_2	= 'B.2.2 Objekte' ;
$B2_3	= 'B.2.3 Ausstattung' ;

$C	= 'C Gutachten' ;
$C1	= 'C.1 Erl&auml;uterungen' ;
$C2	= 'C.2 Allgemeinfl&auml;chen' ;
$C3	= 'C.3 Regelwohnung' ;
$C4	= 'C.4 Regelnutzwerte' ;
$C5	= 'C.5 Zubeh&ouml;r' ;
$C6	= 'C.6 Zuschl&auml;ge' ;
$C7	= 'C.7 Zu- / Abschlagsmatrix' ;
$C8	= '' ;
$C9	= '' ;
$C10 = '' ;
$C11 = 'C.11  Epilog' ;
$C12 = 'C.12  Legende' ;

$C7_1	= 'C.7.1 Abschlagsmatrix' ;
$C7_2	= 'C.7.2 Zuschlagsmatrix' ;

$D	= 'D Beilagen' ;

$anmelden = $_SESSION[ 'anmelden' ] ;
$admin = $anmelden [ 'adm' ] ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="keywords" content="HTML, parifizieren.at, Baukult, Nutzwertgutachten, Regina, Lettner" />
	<meta name="author" content="Hartmut Krämer" />
	<meta name="description" content="Nutzwertgutachten, parifizieren.at, Baukult, E-Mail: kult@baukult.at" />
	<meta name="date" content="20.09.2016" />

	<title>NWG - parifizieren.at</title>

	<link type="text/css" href="<?php echo $pfad . 'CSS/NWG.css'; ?>" rel="stylesheet" />
	<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_buttns.css'; ?>" rel="stylesheet" />
	<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_menu.css'; ?>" rel="stylesheet" />
	<?php
	if( $navi == 'Print' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_Print.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'A.1' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableA1.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.1' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB1.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.2.1' OR  $navi == 'B.2.3' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB2.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.2.2' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB22.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.3' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB3.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.4' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB4.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.5' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB5.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'B.6' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableB6.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.2' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC2.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.3' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC3.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.4' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC4.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.5' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC5.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.6' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC6.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.7.1' OR $navi == 'C.7.2' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC7.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'C.12' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableC12.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'D' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableD.css'; ?>" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'Export' ) {
		?>
		<link type="text/css" href="../CSS/NWG_Export.css" rel="stylesheet" />
		<?php
		}
	elseif( $navi == 'Stammdat' OR $navi == '1' OR $navi == '2' OR $navi == '3') {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_tableStammdaten.css'; ?>" rel="stylesheet" />
		<?php
		}
	else {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_table.css'; ?>" rel="stylesheet" />
		<?php
		}

	if( $d == '1' OR $d == '2' OR $d == '3' ) {
		?>
		<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_Print.css'; ?>" rel="stylesheet" />
		<?php
		}
		?>
	</head>

<body>
	<div id="container">
		<?php
		if( $admin OR $navi == 'index' OR $navi == 'copy' OR $navi == 'Print' OR ( $navi > 0 AND $navi < 4 ) ) {
			?>
			<div id="headerstammd">
			<?php
			}
		elseif( $navi == 'B.2.1' OR $navi == 'B.2.2' OR $navi == 'B.2.3' OR $navi == 'C.7.1' OR $navi == 'C.7.2') {
			?>
			<div id="headerB2">
			<?php
			}
		else {
			?>
			<div id="header">
			<?php
			}
			?>
			<div id="head"></div>
			<div><img src="<?php echo $pfad . 'Bilder/Logo.png'; ?>" alt="Logo parifizieren.at" ID="Logo" /></div>
			<div class="menue">
				<?php
				if( $anabtxt <> '' ) {
					if( $admin ) {
						?>
						<div class="item1"><a href=""><?php echo $topic ; ?></a></div>
						<?php
						}
					else {
						if( $navi == '1' ) {
							?>
							<div class="selected1"><a href="<?php echo $pfad . 'Gutachten/gutachten.php?seite=anfang1'; ?>">
							  In Bearbeitung</a></div>
							<?php
							} 
						else {
							?>
							<div class="item1"><a href="<?php echo $pfad . 'Gutachten/gutachten.php?seite=anfang1'; ?>">
							  In Bearbeitung</a></div>
							<?php
							}
						if( $navi == '2' ) {
							?>
							<div class="selected"><a href="<?php echo $pfad . 'Gutachten/gutachten.php?seite=anfang2'; ?>">
							  Abgeschlossen</a></div>
							<?php
							} 
						else {
							?>
							<div class="item"><a href="<?php echo $pfad . 'Gutachten/gutachten.php?seite=anfang2'; ?>">
							  Abgeschlossen</a></div>
							<?php
							}
						if( $navi == '3' ) {
							?>
							<div class="selected"><a href="<?php echo $pfad . 'Gutachten/gutachten.php?seite=anfang0'; ?>">
							  Alle</a></div>
							<?php
							} 
						else {
							?>
							<div class="item"><a href="<?php echo $pfad . 'Gutachten/gutachten.php?seite=anfang0'; ?>">
							  Alle</a></div>
							<?php
							}
						}
					?>
					<div class="item"><a href="<?php echo $pfad . 'index.php?$seite=Abmelden'; ?>"><?php echo $anabtxt ; ?></a></div>
					<div class="infobox">
						<div class="infobox_linehead1"><?php echo $head1 ; ?></div>
						<div class="infobox_lineitem"> <?php echo $head1txt ; ?></div>
<!--						<div class="infobox_linehead"> <?php echo $head2 ; ?></div>
						<div class="infobox_lineitem"> <?php echo $head2txt ; ?></div> -->
						<div class="infobox_linehead"> <?php echo $head3 ; ?></div>
						<div class="infobox_lineitem"> <?php echo $head3txt ; ?></div>
						<div class="infobox_linehead"> <?php echo $head4 ; ?></div>
						<div class="infobox_lineitem"> <?php echo $head4txt ; ?></div>
						</div>
					<?php
					}
				?>
				</div>
	<?php
	$c = substr( $navi, 0, 1 ) ;
	switch( $c ) {
		case 'A' :
			?>
			<div class="submenue">
				<div class="selected1"><a href="gutachten_a.php?wasid=<?php echo $tableid ; ?>"><?php echo $A ; ?></a></div>
				<div class="item"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B ; ?></a></div>
				<div class="item"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C ; ?></a></div>
				<div class="item"><a href="gutachten_d.php?wasid=<?php echo $tableid ; ?>"><?php echo $D ; ?></a></div>
				</div>	<!-- submenu -->
			<?php
			if( $navi == 'A' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_a1.php?wasid=<?php echo $tableid ; ?>"><?php echo $A1 ; ?></a></div>
					<div class="item"> <a href="gutachten_a2.php?wasid=<?php echo $tableid ; ?>"><?php echo $A2 ; ?></a></div>
					<div class="item"> <a href="gutachten_a3.php?wasid=<?php echo $tableid ; ?>"><?php echo $A3 ; ?></a></div>
					<div class="item"> <a href="gutachten_a4.php?wasid=<?php echo $tableid ; ?>"><?php echo $A4 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'A.1' ) {
				?>
				<div class="submenue">
					<div class="selected1"><a href="gutachten_a1.php?wasid=<?php echo $tableid ; ?>"><?php echo $A1 ; ?></a></div>
					<div class="item"> <a href="gutachten_a2.php?wasid=<?php echo $tableid ; ?>"><?php echo $A2 ; ?></a></div>
					<div class="item"> <a href="gutachten_a3.php?wasid=<?php echo $tableid ; ?>"><?php echo $A3 ; ?></a></div>
					<div class="item"> <a href="gutachten_a4.php?wasid=<?php echo $tableid ; ?>"><?php echo $A4 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'A.2' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_a1.php?wasid=<?php echo $tableid ; ?>"><?php echo $A1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_a2.php?wasid=<?php echo $tableid ; ?>"><?php echo $A2 ; ?></a></div>
					<div class="item"> <a href="gutachten_a3.php?wasid=<?php echo $tableid ; ?>"><?php echo $A3 ; ?></a></div>
					<div class="item"> <a href="gutachten_a4.php?wasid=<?php echo $tableid ; ?>"><?php echo $A4 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'A.3' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_a1.php?wasid=<?php echo $tableid ; ?>"><?php echo $A1 ; ?></a></div>
					<div class="item"> <a href="gutachten_a2.php?wasid=<?php echo $tableid ; ?>"><?php echo $A2 ; ?></a></div>
					<div class="selected"> <a href="gutachten_a3.php?wasid=<?php echo $tableid ; ?>"><?php echo $A3 ; ?></a></div>
					<div class="item"> <a href="gutachten_a4.php?wasid=<?php echo $tableid ; ?>"><?php echo $A4 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'A.4' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_a1.php?wasid=<?php echo $tableid ; ?>"><?php echo $A1 ; ?></a></div>
					<div class="item"> <a href="gutachten_a2.php?wasid=<?php echo $tableid ; ?>"><?php echo $A2 ; ?></a></div>
					<div class="item"> <a href="gutachten_a3.php?wasid=<?php echo $tableid ; ?>"><?php echo $A3 ; ?></a></div>
					<div class="selected"> <a href="gutachten_a4.php?wasid=<?php echo $tableid ; ?>"><?php echo $A4 ; ?></a></div>
					</div>	<!-- submenu -->		
					<?php
				}
			else {
				} // end submenu A
			break ; // end A

//------------ B --------------
		case 'B' :
			?>
			<div class="submenue">
				<div class="item1"><a href="gutachten_a.php?wasid=<?php echo $tableid ; ?>"><?php echo $A ; ?></a></div>
				<div class="selected"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B ; ?></a></div>
				<div class="item"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C ; ?></a></div>
				<div class="item"><a href="gutachten_d.php?wasid=<?php echo $tableid ; ?>"><?php echo $D ; ?></a></div>
				</div>	<!-- submenu -->
			<?php	
			if( $navi == 'B' ) {
				?>
				<div class="submenu">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>
				<?php
				}
			elseif( $navi == 'B.1' ) {
				?>
				<div class="submenue">
					<div class="selected1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'B.2' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'B.3' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="selected"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'B.4' OR $navi == 'B.4.2' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="selected"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
					<?php
				}
			elseif( $navi == 'B.5' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
					<?php
				}
			elseif( $navi == 'B.6' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="selected"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
					<?php
				}
			elseif( $navi == 'B.2.1' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
				<div class="submenue">
					<div class="selected1"><a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_2.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_2 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_3 ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'B.2.2' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
				<div class="submenue">
					<div class="item1"><a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b2_2.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_2 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_3 ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'B.2.3' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2; ?></a></div>
					<div class="item"> <a href="gutachten_b3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B3 ?></a></div>
					<div class="item"> <a href="gutachten_b4.php?wasid=<?php echo $tableid ; ?>"><?php echo $B4 ; ?></a></div>
					<div class="item"> <a href="gutachten_b5.php?wasid=<?php echo $tableid ; ?>"><?php echo $B5 ?></a></div>
					<div class="item"> <a href="gutachten_b6.php?wasid=<?php echo $tableid ; ?>"><?php echo $B6 ; ?></a></div>
					</div>	<!-- submenu -->		
				<div class="submenue">
					<div class="item1"><a href="gutachten_b2_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_1 ; ?></a></div>
					<div class="item"> <a href="gutachten_b2_2.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_2 ; ?></a></div>
					<div class="selected"> <a href="gutachten_b2_3.php?wasid=<?php echo $tableid ; ?>"><?php echo $B2_3 ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			else {
				} // end submenu B
			break ; // end B

//------------ C --------------
		case 'C' :
			?>
			<div class="submenue">
				<div class="item1"><a href="gutachten_a.php?wasid=<?php echo $tableid ; ?>"><?php echo $A ; ?></a></div>
				<div class="item"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B ; ?></a></div>
				<div class="selected"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C ; ?></a></div>
				<div class="item"><a href="gutachten_d.php?wasid=<?php echo $tableid ; ?>"><?php echo $D ; ?></a></div>
				</div>	<!-- submenu -->
			<?php	
			if( $navi == 'C' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>
				<?php
				}
			elseif( $navi == 'C.1' ) {
				?>
				<div class="submenue">
					<div class="selected1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.2' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.3' ) {
				?>
				<link type="text/css" href="<?php echo $pfad . 'CSS/NWG_regelwohng.css'; ?>" rel="stylesheet" />
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.4' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
					<?php
				}
			elseif( $navi == 'C.5' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.6' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.7' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.7.1' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<div class="submenue">
					<div class="selected1"><a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7_1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7_2 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.7.2' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<div class="submenue">
					<div class="item1"><a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7_1 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c7_2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7_2 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.11' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="item"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			elseif( $navi == 'C.12' ) {
				?>
				<div class="submenue">
					<div class="item1"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C1 ; ?></a></div>
					<div class="item"> <a href="gutachten_c2.php?wasid=<?php echo $tableid ; ?>"><?php echo $C2 ; ?></a></div>
					<div class="item"> <a href="gutachten_c3.php?wasid=<?php echo $tableid ; ?>"><?php echo $C3 ; ?></a></div>
					<div class="item"> <a href="gutachten_c4.php?wasid=<?php echo $tableid ; ?>"><?php echo $C4 ; ?></a></div>
					<div class="item"> <a href="gutachten_c5.php?wasid=<?php echo $tableid ; ?>"><?php echo $C5 ; ?></a></div>
					<div class="item"> <a href="gutachten_c6.php?wasid=<?php echo $tableid ; ?>"><?php echo $C6 ; ?></a></div>
					<div class="item"> <a href="gutachten_c7_1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C7 ; ?></a></div>
					<div class="item"> <a href="gutachten_c11.php?wasid=<?php echo $tableid ; ?>"><?php echo $C11 ; ?></a></div>
					<div class="selected"> <a href="gutachten_c12.php"><?php echo $C12 ; ?></a></div>
					</div>	<!-- submenu -->		
				<?php
				}
			else {
				} // end submenu C
			break ; // end C

//------------ D --------------
		case 'D' :
			?>
			<div class="submenue">
				<div class="item1"><a href="gutachten_a.php?wasid=<?php echo $tableid ; ?>"><?php echo $A ; ?></a></div>
				<div class="item"><a href="gutachten_b1.php?wasid=<?php echo $tableid ; ?>"><?php echo $B ; ?></a></div>
				<div class="item"><a href="gutachten_c1.php?wasid=<?php echo $tableid ; ?>"><?php echo $C ; ?></a></div>
				<div class="selected"><a href="gutachten_d.php?wasid=<?php echo $tableid ; ?>"><?php echo $D ; ?></a></div>
				</div>	<!-- submenu -->
			<?php	
			break ; // end D
		default:
			break ;
		}

	if( $navi == 'Regelwohnung' ) {
		?>
		<?php
		}
	?>
			</div><!-- header -->
<!-- EOF -->