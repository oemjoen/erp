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
					mcabang.`Region 1` AS 'WIL',
					dsalesdetail.Cabang AS 'NM_CABANG',
					IFNULL(dsalesdetail.`Cara Bayar`,'Kredit') AS 'KODE_JUAL',
					`No Faktur` AS 'NODOKJDI',
					IFNULL(`No Acu`,'') AS 'NO_ACU',
					dsalesdetail.`Pelanggan` AS 'KODE_PELANGGAN',
					mPelanggan.Kota AS 'KODE_KOTA',
					mPelanggan.`Tipe Pelanggan` AS 'KODE_TYPE',
					IFNULL(mPelanggan.Kode2,'') AS 'KODE_LANG',
					mPelanggan.Pelanggan AS 'NAMA_LANG',
					mPelanggan.Alamat AS 'ALAMAT',
					Prinsipal AS 'JUDUL',
					Produk AS 'KODEPROD',
					NamaProduk AS 'NAMAPROD',
					IFNULL(Jumlah,0) AS 'UNIT',
					IFNULL(`Bonus Faktur`,0) AS 'PRINS',
					IFNULL(Banyak,0) AS 'BANYAK',
					IFNULL(Harga,0) AS 'HARGA',
					IFNULL(`Dsc Cab`,0) AS 'PRSNXTRA',
					IFNULL(`Dsc Pri`,0) AS 'PRINPXTRA',
					IFNULL(Gross,0) AS 'TOT1',
					IFNULL(`Total Value`,0) AS 'NILJU',
					IFNULL(PPN,0) AS PPN,
					IFNULL(`Total COGS`,0) AS 'COGS',
					Salesman AS 'KODESALES',
					DATE(`Tanggal`) AS 'TGLDOK',
					IFNULL(DATE(`Exp Date`),'') AS 'TGLEXP',
					IFNULL(`Batch No`,'') AS 'BATCH',
					dsalesdetail.`Area String` AS 'Area'
			FROM `dsalesdetail`
			LEFT JOIN (SELECT Kode,Kode2,Kota,`Tipe Pelanggan`,Pelanggan,Alamat FROM mPelanggan) AS mPelanggan 
					ON dsalesdetail.`Pelanggan`=`mPelanggan`.`Kode`
			LEFT JOIN (SELECT `Region 1`,Cabang FROM mcabang) AS mcabang 
					ON `dsalesdetail`.`Cabang`=`mcabang`.Cabang
			WHERE `dsalesdetail`.`Cabang`='$cabang' AND MONTH(`Tanggal`)='$mth' AND YEAR(`Tanggal`)='$yr'
			ORDER BY dsalesdetail.`Tanggal`,dsalesdetail.`Counter`";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	//&nbsp
	$content = "<font size='3' align='left'><b>L A P O R A N    P E M B E L I A N<br>PT. SAPTA SARI TAMA<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."</b></font>
	<table border='1' style='border-collapse:collapse'>
		<tr>
			<th bgcolor=silver>NO</th>
			<th bgcolor=silver>WIL</th>
			<th bgcolor=silver>NMCABANG</th>
			<th bgcolor=silver>KODEJUAL</th>
			<th bgcolor=silver>NODOKJDI</th>
			<th bgcolor=silver>NOACU</th>
			<th bgcolor=silver>KODE PELANGGAN</th>
			<th bgcolor=silver>KODE KOTA</th>
			<th bgcolor=silver>KODE TYPE</th>
			<th bgcolor=silver>KODE LANG</th>
			<th bgcolor=silver>NAMA LANG</th>
			<th bgcolor=silver>ALAMAT</th>
			<th bgcolor=silver>JUDUL</th>
			<th bgcolor=silver>KODEPROD</th>
			<th bgcolor=silver>NAMAPROD</th>
			<th bgcolor=silver>UNIT</th>
			<th bgcolor=silver>PRINS</th>
			<th bgcolor=silver>BANYAK</th>
			<th bgcolor=silver>HARGA</th>
			<th bgcolor=silver>PRSNXTRA</th>
			<th bgcolor=silver>PRINPXTRA</th>
			<th bgcolor=silver>TOT1</th>
			<th bgcolor=silver>NILJU</th>
			<th bgcolor=silver>PPN</th>
			<th bgcolor=silver>COGS</th>
			<th bgcolor=silver>KODESALES</th>
			<th bgcolor=silver>TGLDOK</th>
			<th bgcolor=silver>TGLEXP</th>
			<th bgcolor=silver>BATCH</th>
			<th bgcolor=silver>AREA</th>
		</tr>";
	$no=0 ;
	//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	while ($r_data = mysql_fetch_array ($r)) {
		$no++;		
		$content .= "<tr>
						<td align='center'>$no</td>
						<td align='left'>$r_data[WIL]</td>
						<td align='left'>$r_data[NM_CABANG]</td>
						<td align='left'>$r_data[KODE_JUAL]</td>
						<td align='left'>$r_data[NODOKJDI]</td>
						<td align='left'>$r_data[NO_ACU]</td>
						<td align='left'>$r_data[KODE_PELANGGAN]</td>
						<td align='left'>$r_data[KODE_KOTA]</td>
						<td align='left'>$r_data[KODE_TYPE]</td>
						<td align='left'>$r_data[KODE_LANG]</td>
						<td align='left'>$r_data[NAMA_LANG]</td>
						<td align='left'>$r_data[ALAMAT]</td>
						<td align='left'>$r_data[JUDUL]</td>
						<td align='left'>$r_data[KODEPROD]</td>
						<td align='left'>$r_data[NAMAPROD]</td>
						<td align='left'>".format_rupiah_koma($r_data[UNIT])."</td>
						<td align='left'>$r_data[PRINS]</td>
						<td align='left'>".format_rupiah_koma($r_data[BANYAK])."</td>
						<td align='left'>".format_rupiah_koma($r_data[HARGA])."</td>
						<td align='left'>".format_rupiah_koma($r_data[PRSNXTRA])."</td>
						<td align='left'>".format_rupiah_koma($r_data[PRINPXTRA])."</td>
						<td align='left'>".format_rupiah_koma($r_data[TOT1])."</td>
						<td align='left'>".format_rupiah_koma($r_data[NILJU])."</td>
						<td align='left'>".format_rupiah_koma($r_data[PPN])."</td>
						<td align='left'>".format_rupiah_koma($r_data[COGS])."</td>
						<td align='left'>$r_data[KODESALES]</td>
						<td align='left'>$r_data[TGLDOK]</td>
						<td align='left'>$r_data[TGLEXP]</td>
						<td align='left'>$r_data[BATCH]</td>
						<td align='left'>$r_data[Area]</td>
					</tr>"; 
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_PT_".$cabang."_Periode_".$tgl_awal."_sd_".$tgl_akhir.".xls");
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
