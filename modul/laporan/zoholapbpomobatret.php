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
}else if ($kode2=='2')
{
	$bln = '04';
}else if ($kode2=='3')
{
	$bln = '07';
}else if ($kode2=='4')
{
	$bln = '10';
}
else
{
	$bln = '';
}

	if (empty($kode1)|| empty($kode2)||empty($cabang)){
	
	}else{
	$q = "SELECT c.Farmalkes,a.`NamaProduk`,
				SUM(CASE WHEN b.`Group Tipe`='Rumah Sakit' THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS RS, 
				SUM(CASE WHEN b.`Group Tipe`='Apotek' THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS AP, 
				SUM(CASE WHEN b.`Group Tipe`='PBF' THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS PBF, 
				SUM(CASE WHEN b.`Group Tipe`='Pemerintah' THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS PEM, 
				SUM(CASE WHEN b.`Group Tipe`='Swasta' THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS SW,
				SUM(CASE WHEN b.`Group Tipe`IS NULL THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS KOS,	 
				SUM(CASE WHEN b.`Group Tipe`IN ('Rumah Sakit','Apotek','PBF','Pemerintah','Swasta') THEN (a.`Jumlah`+a.`Bonus Faktur`)*-1 ELSE 0 END) AS KePBF	 
			FROM `dsalesdetail` a
			LEFT JOIN (SELECT Cabang,`Kode`,Pelanggan,`Tipe Pelanggan`,`Group Tipe` FROM `mpelanggan`) b 
				ON a.`Cabang`=b.Cabang AND a.`Pelanggan`=b.kode
			LEFT JOIN (SELECT `Kode Produk`,`Farmalkes`,`Prinsipal`,Produk FROM `mproduk`) c
				ON a.`Produk`=c.`Kode Produk`
			WHERE a.`Status`='Retur' AND c.`Farmalkes` IS NOT NULL AND a.`Cabang`='$cabang' 
			AND (a.`Tanggal` BETWEEN 
				DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
				AND 
				LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH)))
			GROUP BY a.`Cabang`,a.`Produk`
			ORDER BY c.`Farmalkes` DESC, c.Produk ASC
		;";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	
	$content = "<p align='left'><font size='3'><b>PELAPORAN RETUR OBAT PERIODE TRIWULAN : ".$kode2." TAHUN ".$kode1." PBF SAPTASARITAMA<br></b></font></p>
	<table border='1' style='border-collapse:collapse'>
		<tr>
			<th rowspan=2 bgcolor=silver>NO</th>
			<th rowspan=2 bgcolor=silver>KODE OBAT</th>
			<th rowspan=2 bgcolor=silver>NAMA OBAT</th>
			<th colspan=5 bgcolor=silver>RETUR MASUK</th>
			<th colspan=3 bgcolor=silver>RETUR KELUAR</th>
		</tr>
		<tr>
			<th bgcolor=silver>DARI RS</th>
			<th bgcolor=silver>DARI APOTEK</th>
			<th bgcolor=silver>DARI PBF</th>
			<th bgcolor=silver>DARI PEMERINTAH</th>
			<th bgcolor=silver>DARI SWASTA</th>
			<th bgcolor=silver>PEMUSNAHAN</th>
			<th bgcolor=silver>KEMBALI KE PABRIK</th>
			<th bgcolor=silver>KEMBALI KE PBF</th>
		</tr>";
	$no=0 ;
	//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	while ($r_data = mysql_fetch_array ($r)) {
		$no++;		
		$content .= "<tr>
					<td align='center'>$no</td>
					<td align='left'>'$r_data[Farmalkes]</td>
					<td align='left'>$r_data[NamaProduk]</td>
					<td align='center'>$r_data[RS]</td>
					<td align='center'>$r_data[AP]</td>
					<td align='center'>$r_data[PBF]</td>
					<td align='center'>$r_data[PEM]</td>
					<td align='center'>$r_data[SW]</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[KePBF]</td>
					<td align='center'>0</td>
					</tr>"; 
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_Obat_Retur_".$cabang."_Triwulan_".$kode2."_Tahun_".$kode2.".xls");
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
