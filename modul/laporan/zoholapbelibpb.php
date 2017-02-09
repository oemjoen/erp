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
	$q = "SELECT 
		  a.`Prinsipal`,
		  a.`Cabang`,
		  b.`Kode Produk` AS kode,
		  b.Produk,
		  a.`No BPB` AS bpb,
		  IFNULL(DATE(a.`Tgl BPB`),'') AS tgl,
		  IFNULL(a.`Batch No`,'') AS BatchNo,
		  IFNULL(DATE(a.`Exp Date`),'') AS expd,
		  a.`Qty Terima` AS JumlahUnit,
		  a.`Hrg Beli Cab` AS HargaBeli,
		  a.`Disc Cab` AS Disc,
		  a.`Bonus` AS BonusBarang,
		  a.`Value BPB` AS TotalPembelian,
		  a.`No DO` AS NoAcuan,
		  a.`Time BPB` AS Vtime,
		  a.`Status BPB` AS stats
		FROM `dbpbdodetail` a
	LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
	WHERE  a.Cabang = '$cabang' AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
			OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
			OR `Status BPB` = 'BPB Koreksi') 
		AND (`Tgl BPB` BETWEEN '$kode1' AND '$kode2')
	ORDER BY a.`Tgl BPB`,a.`Counter BPB`, a.`Time BPB`,a.`Prinsipal`,a.`Produk`";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	//&nbsp
	$content = "<font size='3' align='left'><b>L A P O R A N    B P B<br>PT. SAPTA SARI TAMA<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."</b></font>
	<table border='1' style='border-collapse:collapse'>
		<tr>
			<th bgcolor=silver>NO</th>
			<th bgcolor=silver>PRINSIPAL</th>
			<th bgcolor=silver>CABANG</th>
			<th bgcolor=silver>KODE PRODUK</th>
			<th bgcolor=silver>NAMA PRODUK</th>
			<th bgcolor=silver>NO DOKUMEN</th>
			<th bgcolor=silver>TGL DOKUMEN</th>
			<th bgcolor=silver>BATCH NO</th>
			<th bgcolor=silver>EXP DATE</th>
			<th bgcolor=silver>JUMLAH</th>
			<th bgcolor=silver>HARGA BELI</th>
			<th bgcolor=silver>% DISC</th>
			<th bgcolor=silver>BONUS</th>
			<th bgcolor=silver>TOTAL</th>
			<th bgcolor=silver>NO ACUAN</th>
			<th bgcolor=silver>_</th>
			<th bgcolor=silver>STATUS</th>
		</tr>";
	$no=0 ;
	//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	while ($r_data = mysql_fetch_array ($r)) {
		$no++;		
		$content .= "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Prinsipal]</td>
					<td align='left'>$r_data[Cabang]</td>
					<td align='left'>$r_data[kode]</td>
					<td align='left'>$r_data[Produk]</td>
					<td align='left'>$r_data[bpb]</td>
					<td align='left'>$r_data[tgl]</td>
					<td align='left'>$r_data[BatchNo]</td>
					<td align='left'>$r_data[expd]</td>
					<td align='center'>".format_rupiah_koma($r_data[JumlahUnit])."</td>
					<td align='center'>".format_rupiah_koma($r_data[HargaBeli])."</td>
					<td align='center'>$r_data[Disc]</td>
					<td align='center'>".format_rupiah_koma($r_data[BonusBarang])."</td>
					<td align='center'>".format_rupiah_koma($r_data[TotalPembelian])."</td>
					<td align='left'>$r_data[NoAcuan]</td>
					<td align='left'>$r_data[Vtime]</td>
					<td align='left'>$r_data[stats]</td>
					</tr>"; 
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_Pembelian_".$cabang."_Periode_".$tgl_awal."_sd_".$tgl_akhir.".xls");
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
