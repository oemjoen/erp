<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';

$kode1	= jin_date_sql($_GET['kode1']);
$kode2	= jin_date_sql($_GET['kode2']);
$cabang	= $_GET['cabang'];

if ($cabang == 'KPS')
	{
		$where2="";
		$where3="";
	}
else{
		$where2="cabang='$cabang' AND ";
		$where3="where cabang='$cabang' ";
	}

	if (empty($kode1) && empty($kode2)){
	$q = "SELECT cabang,kode_pr,tanggal_pr,kode_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM 
					(
					SELECT cabang,kode_pr,tanggal_pr,kode_po,tanggal_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM `vservprpobtb`  $where3 UNION
					SELECT cabang,kode_pr,tanggal_pr,kode_po,tanggal_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM `vservprpobtbpre`  $where3  UNION
					SELECT cabang,kode_pr,tanggal_pr,kode_po,tanggal_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM `vservprpobtbpsi`  $where3 
					)btb 
					ORDER BY `cabang`,`tanggal_pr`";
	}else{
	$q = "SELECT cabang,kode_pr,tanggal_pr,kode_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM 
					(
					SELECT cabang,kode_pr,tanggal_pr,kode_po,tanggal_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM `vservprpobtb` WHERE $where2 `tanggal_pr` BETWEEN '$kode1' AND '$kode2' UNION
					SELECT cabang,kode_pr,tanggal_pr,kode_po,tanggal_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM `vservprpobtbpre` WHERE $where2 `tanggal_pr` BETWEEN '$kode1' AND '$kode2'  UNION
					SELECT cabang,kode_pr,tanggal_pr,kode_po,tanggal_po,kode_supplier,kode_prinsipal,kode_barang,jumlah_PR,jumlah_PO,jumlah_BTB FROM `vservprpobtbpsi` WHERE $where2 `tanggal_pr` BETWEEN '$kode1' AND '$kode2' 
					)btb 
					ORDER BY `cabang`,`tanggal_pr`";		
	}
	//echo $q;	
	$r = mysql_query($q);
	$tgl = date('d M Y');
	if (empty($kode1) && empty($kode2)){
		$content = "<h3>LAPORAN SERVICE LEVEL PR / PO / BTB (ALL) CABANG $cabang</h3><br/>
		<table width='390' border='1' style='border-collapse:collapse'>
			<tr>
				<th>No</th>
				<th>CABANG</th>
				<th>KODE PR</th>
				<th>TANGGAL PR</th>
				<th>KODE PO</th>
				<th>KODE BTB</th>
				<th>KODE SUPPLIER</th>
				<th>KODE PRINSIPAL</th>
				<th>KODE BARANG</th>
				<th>JUMLAH PR</th>
				<th>JUMLAH PO</th>
				<th>JUMLAH BTB</th>
			</tr>";
			}
	else {
		$content = "<h3>LAPORAN SERVICE LEVEL PR / PO / BTB (dari Tanggal $kode1 sampai dengan $kode2) CABANG $cabang  $q</h3><br/>
		<table width='390' border='1' style='border-collapse:collapse'>
			<tr>
				<th>No</th>
				<th>CABANG</th>
				<th>KODE PR</th>
				<th>TANGGAL PR</th>
				<th>KODE PO</th>
				<th>KODE BTB</th>
				<th>KODE SUPPLIER</th>
				<th>KODE PRINSIPAL</th>
				<th>KODE BARANG</th>
				<th>JUMLAH PR</th>
				<th>JUMLAH PO</th>
				<th>JUMLAH BTB</th>
			</tr>";
	}
	$no = 1;
	while ($d = mysql_fetch_array ($r)) {
	$kodebtbpr = kode_btb_from_pr($d[kode_pr],$d[kode_barang]);
		$content .= "
		<tr>
			<td align='center'>$no</td>
			<td>$d[cabang]</td>
			<td>$d[kode_pr]</td>
			<td>$d[tanggal_pr]</td>
			<td>$d[kode_po]</td>
			<td>$kodebtbpr</td>
			<td>$d[kode_supplier]</td>
			<td>$d[kode_prinsipal]</td>
			<td>$d[kode_barang]</td>
			<td>$d[jumlah_PR]</td>
			<td>$d[jumlah_PO]</td>
			<td>$d[jumlah_BTB]</td>
		</tr>";
		$no++;

	}
	$content .= "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=lap_service_level_btb.xls");
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
