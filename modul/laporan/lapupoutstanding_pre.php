<?php
date_default_timezone_set('Asia/Jakarta'); 

include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';

$tanggal = date(d).date(m).date(Y);
$kode1	= jin_date_sql($_GET['kode1']);
$kode2	= jin_date_sql($_GET['kode2']);
$cabang = $_GET['cabang'];

	if ($cabang =='KPS')
	{
		$q = "SELECT kode_beli,tgl_beli,kode_supplier,b.`namasupplier`,cabang,SUM(a.`jumlah_beli` * c.`hargajual`) AS total FROM pembelian_usulan_pre a
				LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
				LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
				WHERE `kode_pr_approv` IS NULL
				GROUP BY kode_beli
				ORDER BY `tgl_beli` DESC,`cabang`,`kode_beli`";//echo "--1--".$query2."</br>";
	}
	else
	{
		$q = "SELECT kode_beli,tgl_beli,kode_supplier,b.`namasupplier`,cabang,SUM(a.`jumlah_beli` * c.`hargajual`) AS total FROM pembelian_usulan_pre a
				LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
				LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
				WHERE `kode_pr_approv` IS NULL AND cabang='$cabang'
				GROUP BY kode_beli
				ORDER BY `tgl_beli` DESC,`cabang`,`kode_beli`";//echo "--2--".$query2."</br>";
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	$content = "<h3>LAPORAN OUTSTADING PR</h3><br/>
	<table width='390' border='1' style='border-collapse:collapse'>
		<tr>
				<th>No.</th>
				<th>Kode Usulan</th>
				<th>Tanggal Usulan</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Cabang</th>
		</tr>";
	$no = 1;
	while ($d = mysql_fetch_array ($r)) {
				$suppliername = nama_supplier_saja($d[SUPPLIER]);
		$content .= "
		<tr>
			<td align='center'>$no</td>
			<td align='center'>$d[kode_beli]</td>
			<td align='center'>$d[tgl_beli]</td>
			<td>$d[kode_supplier]</td>
			<td>$d[namasupplier]</td>
			<td>$d[cabang]</td>
		</tr>";
		$no++;

	}
	$content .= "</table>";
		
		//laporan ke excel
		header("Content-Type: application/force-download");
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=\"Laporan_Usulan_Pre_Outstanding_$tanggal.xls\"");
		header("Pragma: max-age=0");
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
