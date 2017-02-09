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


	if (empty($kode1)|| empty($kode2)){
	
	}else{
	$q = "SELECT IFNULL(xx.Asal,yy.`Supplier`) AS Asal,xx.salur,xx.Kode,xx.Nama,xx.Jumlah,yy.`Supplier` FROM(
					SELECT a.`Supplier` AS Asal,a.Cabang AS salur,a.`Produk` AS Kode,b.`Produk` AS Nama,
						SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) AS Jumlah 
					FROM `dbpbdodetail` a, `mproduk` b
					WHERE 
						(a.`Cabang`!='Pusat' AND a.`Cabang`!='Dummy' AND a.`Cabang`!='Dummy22') 
						AND (a.`Status BPB` = 'Open' OR a.`Status BPB` = 'DO' OR a.`Status BPB` = 'BPB Retur' 
							OR a.`Status BPB` = 'BPB Relokasi' OR a.`Status BPB` = 'BPB Koreksi')
						AND ((a.`Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'))
						AND b.`Prinsipal` IN ('ALKES','ALKES-LAIN','KARINDO','AXION')
						AND a.`Produk`=b.`Kode Produk` 
					GROUP BY a.`Cabang`,a.`Produk`
					ORDER BY a.`Cabang`,a.`Supplier`,a.`Produk`)xx,(SELECT DISTINCT Produk,`Supplier` FROM `dbpbdodetail` WHERE `supplier` IS NOT NULL) yy
					WHERE xx.Kode = yy.`Produk`
					ORDER BY xx.`Nama`,xx.`Asal`,xx.`salur`";		
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
					<td align='center' nowrap>$no</td>
					<td align='left' nowrap>$r_data[Nama]</td>
					<td align='center' nowrap>$r_data[Jumlah]</td>
					<td align='left' nowrap>$r_data[Asal]</td>
					<td align='left' nowrap>Cabang ".strtoupper($r_data[salur])."</td>
					<td align='center' nowrap></td>
					</tr>"; 
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_Alkes_Pusat_Periode_".$tgl_awal."_sd_".$tgl_akhir.".xls");
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
