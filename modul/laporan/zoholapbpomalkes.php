<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';
include '../../inc/fungsi_rupiah.php';

$kode1	= jin_date_sql($_GET['kode1']);
$cabang	= $_GET['cabang'];
$kode2	= jin_date_sql($_GET['kode2']);

//cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode2));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1.'+ 1 months'));


	if (empty($kode1)|| empty($cabang)){
	
	}else{
	$q = "SELECT 'Pusat' AS Asal,Cabang,`Nama Faktur` as salur ,det.`Produk`,`NamaProduk`, ifnull((SUM(`Jumlah`) + SUM(`Bonus Faktur`)),0) AS  `Jumlah`
					FROM `dsalesdetail` det
					LEFT JOIN (SELECT `Kode Produk`,Prinsipal FROM `mproduk` WHERE `Prinsipal` IN ('ALKES','ALKES-LAIN','KARINDO','AXION')) prod
						ON det.`Produk`=prod.`Kode Produk`
					WHERE prod.`Kode Produk` IS NOT NULL AND (`Tanggal` BETWEEN '$tgl_awal' AND '$tgl_akhir') AND Cabang ='$cabang'
					GROUP BY det.`Cabang`,det.`Produk`,det.`Pelanggan`
					HAVING (SUM(`Jumlah`) + SUM(`Bonus Faktur`)) != 0
					ORDER BY det.`Cabang`,prod.`Prinsipal`,det.`Produk`,det.`Pelanggan`";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	//&nbsp
	$content = "<font size='3' align='left'><b>LAPORAN HASIL KEGIATAN PENYALURAN <br>ALAT KESEHATAN PT. SAPTA SARI TAMA<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."</b></font>
	<table border='1' style='border-collapse:collapse'>
		<tr>
			<th bgcolor=silver>NO</th>
			<th bgcolor=silver>NAMA PRODUK</th>
			<th bgcolor=silver>JUMLAH</th>
			<th bgcolor=silver>ASAL PRODUK</th>
			<th bgcolor=silver>DISALURKAN KEPADA</th>
			<th bgcolor=silver>KETERANGAN</th>
		</tr>";
	$no=0 ;
	//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	while ($r_data = mysql_fetch_array ($r)) {
		$no++;		
		$content .= "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[NamaProduk]</td>
					<td align='center'>$r_data[Jumlah]</td>
					<td align='left'>$r_data[Asal]</td>
					<td align='left'>$r_data[salur]</td>
					<td align='center'></td>
					</tr>"; 
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_Alkes_".$cabang."_Periode_".$tgl_awal."_sd_".$tgl_akhir.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $content;
				/*
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=lap_barang.doc");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $content;	
		*/

?>
