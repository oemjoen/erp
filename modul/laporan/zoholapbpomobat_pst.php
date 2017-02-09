<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';
include '../../inc/fungsi_rupiah.php';

date_default_timezone_set('Asia/Jakarta'); 
//Tanggal
//$kode1	= jin_date_sql($_GET['kode1']);
$kode1	= ($_GET['kode1']);
//Triwulan
$kode2	= $_GET['kode2'];
//cabang
$cabang	= $_GET['cabang'];

/* //cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode1));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1.'+ 1 months')); */

if($kode2=='1')
{
	$bln = '01';
	$bln1 = '01';
	if($kode1=='2015')
	{
	$bln1 = '0115';
	}
}else if ($kode2=='2')
{
	$bln = '04';
	$bln1 = '04';
	if($kode1=='2015')
	{
	$bln1 = '0415';
	}
}else if ($kode2=='3')
{
	$bln = '07';
	$bln1 = '07';
	if($kode1=='2015')
	{
	$bln1 = '0715';
	}
}else if ($kode2=='4')
{
	$bln = '10';
	$bln1 = '10';
	if($kode1=='2015')
	{
	$bln1 = '1015';
	}
}
else
{
	$bln = '';
	$bln1 = '';
}

	if (empty($kode1)|| empty($kode2)){
	
	}else{
	$q = "SELECT a.`Produk` AS Kode,b.`Farmalkes`,b.`Produk` AS Nama,0 AS StokAwal,
							SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) AS MasukPabrik, 
							SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) AS KeluarPBF,
							0 AS Akhir,MAX(a.`Hrg Beli Cab`) AS HJD 
						FROM `dbpbdodetail` a, `mproduk` b
						WHERE 
							(a.`Cabang`!='Pusat' AND a.`Cabang`!='Dummy' AND a.`Cabang`!='Dummy22') AND 
							(a.`Status BPB` = 'Open' OR a.`Status BPB` = 'DO' OR a.`Status BPB` = 'BPB Retur' 
								OR a.`Status BPB` = 'BPB Relokasi' OR a.`Status BPB` = 'BPB Koreksi')
							AND (a.`Tgl BPB` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),
								INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
								AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH)))
							AND a.`Produk`=b.`Kode Produk` AND b.`Farmalkes` IS NOT NULL
						GROUP BY b.`Farmalkes`
		;";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	
	$content = "<p align='left'><font size='3'><b>PELAPORAN OBAT PERIODE TRIWULAN : ".$kode2." TAHUN ".$kode1." PBF SAPTASARITAMA<br></b></font></p>
	<table border='1' style='border-collapse:collapse'>
		<tr>
			<th rowspan=2 bgcolor=silver>NO</th>
			<th rowspan=2 bgcolor=silver>KODE OBAT</th>
			<th rowspan=2 bgcolor=silver>NAMA OBAT</th>
			<th rowspan=2 bgcolor=silver>STOK AWAL</th>
			<th colspan=2 bgcolor=silver>JUMLAH PEMASUKAN MASUK</th>
			<th colspan=5 bgcolor=silver>JUMLAH PENGELUARAN</th>
			<th rowspan=2 bgcolor=silver>HJD</th>
		</tr>
		<tr>
			<th bgcolor=silver>MASUK PABRIK</th>
			<th bgcolor=silver>MASUK PBF</th>
			<th bgcolor=silver>KELUAR RS</th>
			<th bgcolor=silver>KELUAR APOTEK</th>
			<th bgcolor=silver>KELUAR PBF</th>
			<th bgcolor=silver>KELUAR PEMERINTAH</th>
			<th bgcolor=silver>KELUAR SWASTA</th>
		</tr>";
	$no=0 ;
	//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	while ($r_data = mysql_fetch_array ($r)) {
		$no++;		
		$content .= "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Farmalkes]</td>
					<td align='left'>$r_data[Nama]</td>
					<td align='center'>$r_data[StokAwal]</td>
					<td align='center'>$r_data[MasukPabrik]</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[KeluarPBF]</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[HJD]</td>
					</tr>"; 
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_Obat_Pusat_Triwulan_".$kode2."_Tahun_".$kode1.".xls");
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
