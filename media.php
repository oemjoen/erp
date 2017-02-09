<?php
$page = $_SERVER['PHP_SELF'];
$sec = "10";
include 'inc/cek_session.php';
include 'inc/inc.koneksi.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<!--<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page.'?module=home'?>'">-->
<title>Sistem Web Logistik</title>
<link rel="stylesheet" href="css/icon.css" type="text/css" />
<link rel="stylesheet" href="css/superfish.css" type="text/css" />
<link rel="stylesheet" href="css/style_content.css" type="text/css" />
<link rel="stylesheet" href="css/style_tabel.css" type="text/css" />


<script type="text/javascript" src="js/jquery-1.4.js"></script>
<script type="text/javascript" src="js/hoverIntent.js"></script>
<!-- untuk menu superfish -->
<script type="text/javascript" src="js/superfish.js"></script>

<!-- untuk datepicker -->
<link type="text/css" href="css/ui.all.css" rel="stylesheet" />   
<script type="text/javascript" src="js/ui.core.js"></script>
<script type="text/javascript" src="js/ui.datepicker.js"></script>
<script type="text/javascript" src="js/ui.datepicker-id.js"></script>

<!-- untuk autocomplite -->
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>

<!-- plugin untuk tab -->
<link type="text/css" href="css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	   $('ul.sf-menu').superfish();
  });
</script>
</head>

<body>
<div class="box">
<div class="border">
<div class="style">
	<div class="header">
    	<span class="title">
        	<div align="center">
<!--        		<img src="images/header.jpg" width="840" height="120" />-->
            </div>
        </span>
    </div>
	<div class="menu">
   	 	<ul class="sf-menu">
			<li><a href="?module=home" class="icon home">Home</a></li>	
			<?php 
				//echo $userrr;
				//if ($cabangrr=='KPS'){ ?>
				<li>
					<a href="#" class="icon master">AP MITRA</a>
					<ul>				
						<li><a href="?module=tordsp" class="icon listdata">SP AP</a></li>
						<li><a href="?module=smsg" class="icon listdata">SMS ORDER</a></li>
					</ul>
				</li>			
<!--				<li>
					<a href="#" class="icon returbeli">Usulan Retur</a>
					<ul>				
						<li><a href="?module=retur_konfirm" class="icon listdata">Usulan Retur Beli</a></li>
					</ul>
				</li>-->			
			
				<li>
					<a href="#" class="icon master">Laporan</a>
					<ul>				
<!--						<li><a href="?module=kartugudang" class="icon supplier">Kartu Gudang</a></li> -->
						<li><a href="?module=kartugudang2" class="icon listdata">Kartu Gudang</a></li>
						<li><a href="?module=kartugudang_r" class="icon listdata">Kartu Gudang Range</a></li>
						<li><a href="?module=mutgud" class="icon listdata">Mutasi Gudang</a></li>
							<li><a href="#" class="icon listdata">Laporan BPOM</a>
								<ul>
									<li><a href="?module=alkes" class="icon listdata">Laporan Alkes</a></li>
									<li><a href="?module=obat" class="icon listdata">Laporan Obat</a></li>
									<li><a href="?module=retobat" class="icon listdata">Laporan Obat Retur</a></li>
									<li><a href="?module=obatpsipre" class="icon listdata">Laporan Prekursor / Psikotropika</a></li>
									<li><a href="?module=lintas" class="icon listdata">Laporan Lintas Provinsi</a></li>
									<li><a href="?module=obatoot" class="icon listdata">BPOM Laporan OOT</a></li>
								</ul>	
							</li>
					</ul>
				</li>
				<li>
					<a href="#" class="icon master">Laporan Cabang</a>
					<ul>				
						<li><a href="?module=lapbeli" class="icon listdata">Laporan Pembelian</a></li>
						<li><a href="?module=lapbelibpb" class="icon listdata">Laporan BPB</a></li>
						<li><a href="?module=lapbelibkb" class="icon listdata">Laporan BKB</a></li>
						<li><a href="?module=pt" class="icon listdata">Laporan PT</a></li>
					</ul>
				</li>			
			<?php //} ?>
			<?php if ($cabangrr=='Pusat'){ ?>
				<li>
					<a href="#" class="icon master">Laporan Pusat</a>
					<ul>				
						<li><a href="?module=lapbeli" class="icon listdata">Laporan Pembelian</a></li>
							<li><a href="#" class="icon listdata">Laporan BPOM Pusat</a>
								<ul>
									<li><a href="?module=alkespst" class="icon listdata">BPOM Laporan Alkes</a></li>
									<li><a href="?module=obatpst" class="icon listdata">BPOM Laporan Obat</a></li>
									<li><a href="?module=retobatpst" class="icon listdata">BPOM Laporan Obat Retur</a></li>
									<li><a href="?module=obatpsiprepst" class="icon listdata">BPOM Laporan Prekursor / Psikotropika</a></li>
								</ul>
							</li>	
						<li><a href="?module=ceks" class="icon listdata">Cek Stok</a></li>							
					</ul>
				</li>

				
			<?php } ?>			
          <li><a href="logout.php" class="icon keluar">Keluar</a></li>	
		</ul>
    </div>
	<!--awal content -->
    <div class="content">
    	<?php
			include 'content.php';
		?>
    </div>
    <!--akhir content -->
    <div class="footer" align="center">
    	<p>Copyright &copy; Team IT <span class="cls_hdt">SST</span> 2016</p>
    	<p align="right">WebERP-SST <span class="cls_hdt">Ver 1.0</span>.7.6.16</p>
    </div>
</div>
</div>
</div>

</body>
</html>