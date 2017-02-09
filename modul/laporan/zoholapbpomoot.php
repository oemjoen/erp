<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';
include '../../inc/fungsi_rupiah.php';

date_default_timezone_set('Asia/Jakarta'); 
//Tanggal
$kode1	= jin_date_sql($_GET['kode1']);
//Produk
$kode2	= $_GET['kode2'];
//cabang
$cabang	= $_GET['cabang'];


//cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode1));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1));


	if (empty($kode1)|| empty($cabang)){
	
	}else{
		
	$sqlprod = "SELECT DISTINCT * FROM( 
					SELECT 
						a.Produk,b.Produk AS NamaProduk, 
						`Batch No` AS batch,
						(`Exp Date`) AS expr
					FROM `dbpbdodetail` a
					LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
					WHERE  a.Cabang = '$cabang' AND 
						b.Kategori IN ('OOT') AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
							OR `Status BPB` = 'BKB Retur' 
							OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
							OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
							OR `Status BPB` = 'BPB Koreksi') AND `Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'
					UNION ALL 
					SELECT a.Produk,`namaproduk`,
						`Batch No` AS batch, 
						(`Exp Date`) AS expr
					FROM `dsalesdetail` a
					LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
					WHERE  a.Cabang = '$cabang' AND 
					b.Kategori IN ('OOT') AND(`Status`='Faktur' OR `Status`='Retur') 
					AND a.Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'  
					)a 
					ORDER BY a.Produk";	

	
	$q = "SELECT * FROM( 
						SELECT 
							a.Produk,b.Produk AS NamaProduk,
							DATE(`Tgl BPB`) AS `Tanggal`, 
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN IFNULL(`Batch No`,'') 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN `No BPB` 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN 'PUSAT' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN `No BPB` 
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN 'PUSAT' 
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))*-1 
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN IFNULL(`Batch No`,'') 
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch,
							IFNULL(DATE(`Exp Date`),'') AS expr, 
							`Time BPB` AS dTime, `Counter BPB` AS counter, `Keterangan` AS Keterangan
						FROM `dbpbdodetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cabang' AND
							b.Kategori IN ('OOT') AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
								OR `Status BPB` = 'BKB Retur' 
								OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
								OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
								OR `Status BPB` = 'BPB Koreksi') AND `Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'
					UNION ALL 
						SELECT a.Produk,`namaproduk`,DATE(`Tanggal`) AS `Tanggal`,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN IFNULL(`Batch No`,'')			
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `No Faktur` 			
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `Nama Faktur`			
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0))) * -1			
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `No Faktur`
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `Nama Faktur` 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) ) 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN IFNULL(`Batch No`,'')
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch, 
							IFNULL(DATE(`Exp Date`),'') AS expr,
							`Time` AS dTime,`Counter` AS counter, '' AS Keterangan
						FROM `dsalesdetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cabang' AND
						b.Kategori IN ('OOT') AND(`Status`='Faktur' OR `Status`='Retur') 
						AND Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'  
					)a 
					ORDER BY a.Produk,a.Tanggal,a.Counter,a.dTime";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	//echo $q;
	//&nbsp
	$content = "<font size='3' align='left'><b>LAPORAN OOT, PT. SAPTA SARI TAMA<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."</b></font>
	<table border='1' style='border-collapse:collapse'>
	<tr>
		<th bgcolor=silver>NO</th>
		<th bgcolor=silver>NAMA PRODUK</th>
		<th bgcolor=silver>TANGGAL</th>
		<th bgcolor=silver>SALDO AWAL</th>
		<th bgcolor=silver>BATCH AWAL</th>
		<th bgcolor=silver>DOKUMEN MASUK</th>
		<th bgcolor=silver>SUMBER</th>
		<th bgcolor=silver>JUMLAH MASUK</th>
		<th bgcolor=silver>BATCH MASUK</th>
		<th bgcolor=silver>DOKUMEN KELUAR</th>
		<th bgcolor=silver>TUJUAN</th>
		<th bgcolor=silver>JUMLAH KELUAR</th>
		<th bgcolor=silver>BATCH KELUAR</th>
		<th bgcolor=silver>SALDO AKHIR</th>
		<th bgcolor=silver>BATCH AKHIR</th>
		<th bgcolor=silver>EXPIRED DATE</th>
	</tr>";

	$queryprod = mysql_query($sqlprod);
	while($p_data=mysql_fetch_array($queryprod))
	{
		$content .=  "<tr>
		<td align='center'>Awal</td>
		<td align='left'>$p_data[NamaProduk]</td>
		<td align='center'>$tgl_awal</td>
		<td align='center'>0</td>
		<td align='center'>$p_data[batch]</td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'>$p_data[expr]</td>
		</tr>";

	$content .=  cabDataOOT($p_data['Produk'],$cabang,$tgl_awal,$tgl_akhir,$p_data[batch],$p_data[expr])[0];
	//echo cabDataPsiPre($p_data['Produk'],$cabang,$tgl_awal,$tgl_akhir,$p_data[batch],$p_data[expr])[2];
	$saldoAkhir = cabDataOOT($p_data['Produk'],$cabang,$tgl_awal,$tgl_akhir,$p_data[batch],$p_data[expr])[1];

		$content .=  "<tr>
		<td align='center'>Akhir</td>
		<td align='left'>$p_data[NamaProduk]</td>
		<td align='center'>$tgl_akhir</td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'>$saldoAkhir</td>
		<td align='center'>$p_data[batch]</td>
		<td align='center'>$p_data[expr]</td>
		</tr>";



	}
	
	// $no=0 ;
	////fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
	// while ($r_data = mysql_fetch_array ($r)) {
		// $no++;		
		// $content .= "<tr>
					// <td align='center'>$no</td>
					// <td align='left'>$r_data[NamaProduk]</td>
					// <td align='center'>$r_data[Tanggal]</td>
					// <td align='center'></td>
					// <td align='center'>$r_data[batch]</td>
					// <td align='left'>$r_data[NoDokMasuk]</td>
					// <td align='left'>$r_data[sumber]</td>
					// <td align='center'>$r_data[JumlahMasuk]</td>
					// <td align='center'>$r_data[BatchMasuk]</td>
					// <td align='left'>$r_data[NoDokKeluar]</td>
					// <td align='left'>$r_data[tujuan]</td>
					// <td align='center'>$r_data[JumlahKeluar]</td>
					// <td align='center'>$r_data[BatchKeluar]</td>
					// <td align='center'></td>
					// <td align='center'></td>
					// <td align='center'>$r_data[expr]</td>
					// </tr>"; 
	// }
		$content .=  "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Laporan_PsiPre_".$cabang."_Periode_".$tgl_awal."_sd_".$tgl_akhir.".xls");
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
