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


$cektgl = date(m).date(Y);
$cektgl1 = $mth.$yr; 

	if($cektgl==$cektgl1)
	{
	$q	= "SELECT Cabang,b.Prinsipal,a.Produk,b.Produk AS `NamaProduk`,
					SUM(awal) AS awal,SUM(masuk) AS masuk,SUM(keluar) AS keluar, 
					(SUM(awal)+ SUM(masuk)- SUM(keluar)) AS akhir
					
					FROM (
					
					SELECT Cabang,produk,SUM(`SAwal$mth`) AS awal, 0 AS masuk, 0 AS keluar, 0 AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status BPB` IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ABS((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))ELSE 0 END) AS keluar,
						0 AS akhir
					FROM `dbpbdodetail`
					WHERE cabang='$cabang' AND MONTH(`Tgl BPB`)='$mth' AND YEAR(`Tgl BPB`)='$yr'
					GROUP BY Cabang,Produk,`Status BPB`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status`='Retur' THEN ABS(`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status`='Faktur' THEN (`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS keluar,
						0 AS akhir
						FROM `dsalesdetail`
					WHERE cabang='$cabang' AND MONTH(`Tanggal`)='$mth' AND YEAR(`Tanggal`)='$yr'
					GROUP BY Cabang,Produk,`Status`
					
					UNION ALL
					
					SELECT Cabang,produk,0 AS awal, 0 AS masuk, 0 AS keluar, SUM(`SAwal$mth1`) AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					)a
					LEFT JOIN (SELECT `Prinsipal`,`Pabrik`,`Kode Produk`,`Produk` FROM `mproduk`) AS b ON a.Produk=b.`Kode Produk` 
					GROUP BY Cabang,Produk
					ORDER BY Cabang,b.Prinsipal,a.Produk";
	
	}else
	{
	$q	= "SELECT Cabang,b.Prinsipal,a.Produk,b.Produk AS `NamaProduk`,
					SUM(awal) AS awal,SUM(masuk) AS masuk,SUM(keluar) AS keluar, 
					SUM(akhir) AS akhir
					
					FROM (
					
					SELECT Cabang,produk,SUM(`SAwal$mth`) AS awal, 0 AS masuk, 0 AS keluar, 0 AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status BPB` IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ABS((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))ELSE 0 END) AS keluar,
						0 AS akhir
					FROM `dbpbdodetail`
					WHERE cabang='$cabang' AND MONTH(`Tgl BPB`)='$mth' AND YEAR(`Tgl BPB`)='$yr'
					GROUP BY Cabang,Produk,`Status BPB`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status`='Retur' THEN ABS(`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status`='Faktur' THEN (`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS keluar,
						0 AS akhir
						FROM `dsalesdetail`
					WHERE cabang='$cabang' AND MONTH(`Tanggal`)='$mth' AND YEAR(`Tanggal`)='$yr'
					GROUP BY Cabang,Produk,`Status`
					
					UNION ALL
					
					SELECT Cabang,produk,0 AS awal, 0 AS masuk, 0 AS keluar, SUM(`SAwal$mth1`) AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					)a
					LEFT JOIN (SELECT `Prinsipal`,`Pabrik`,`Kode Produk`,`Produk` FROM `mproduk`) AS b ON a.Produk=b.`Kode Produk` 
					GROUP BY Cabang,Produk
					ORDER BY Cabang,b.Prinsipal,a.Produk";
	
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	//&nbsp
	$content = "<font size='3' align='left'><b>L A P O R A N   M U T A S I   G U D A N G<br>PT. SAPTA SARI TAMA<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."</b></font>
	<table border='1' style='border-collapse:collapse'>
		<tr>
			<th bgcolor=silver>NO</th>
			<th bgcolor=silver>CABANG</th>
			<th bgcolor=silver>PRINSIPAL</th>
			<th bgcolor=silver>PRODUK</th>
			<th bgcolor=silver>NAMA PRODUK</th>
			<th bgcolor=silver>AWAL</th>
			<th bgcolor=silver>MASUK</th>
			<th bgcolor=silver>KELUAR</th>
			<th bgcolor=silver>AKHIR</th>
		</tr>";
	$no=0 ;
	//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	while ($r_data = mysql_fetch_array ($r)) {
		if($r_data[awal]==0 && $r_data[masuk]==0 && $r_data[keluar]==0 && $r_data[akhir]==0)
		{
		}else
		{		
		$no++;		
		$content .= "<tr>
					<td align='center'>$no</td>
						<td align='left'>$r_data[Cabang]</td>
						<td align='left'>$r_data[Prinsipal]</td>
						<td align='left'>$r_data[Produk]</td>
						<td align='left'>$r_data[NamaProduk]</td>
						<td align='right'>".format_rupiah_koma($r_data[awal])."</td>
						<td align='right'>".format_rupiah_koma($r_data[masuk])."</td>
						<td align='right'>".format_rupiah_koma($r_data[keluar])."</td>
						<td align='right'>".format_rupiah_koma($r_data[akhir])."</td>
					</tr>"; 
		}
	}
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Mutasu_Gudang_".$cabang."_Periode_".$tgl_awal."_sd_".$tgl_akhir.".xls");
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
