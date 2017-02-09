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
		$q = "SELECT * FROM (
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprins`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_pre a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspre`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_psi a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspsi`) po  ON prpo.kode_beli=po.`kode_beli`
								)servlvlpopr HAVING value_pr <> 0 AND kode_po=''
								ORDER BY tgl_pr DESC,cabang,kode_pr";//echo "--1--".$query2."</br>";
	}
	else
	{
		$q = "SELECT * FROM (
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprins`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_pre a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspre`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_psi a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspsi`) po  ON prpo.kode_beli=po.`kode_beli`
								)servlvlpopr HAVING value_pr <> 0 AND kode_po='' AND cabang='$cabang'
								ORDER BY tgl_pr DESC,cabang,kode_pr";//echo "--2--".$query2."</br>";
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	$content = "<h3>LAPORAN OUTSTADING PR </h3><br/>
	<table width='390' border='1' style='border-collapse:collapse'>
		<tr>
				<th>No.</th>
				<th>Kode PR</th>
				<th>Tanggal Beli</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Value PR</th>
				<th>Prinsipal</th>
				<th>Kode PO</th>
				<th>Tanggal PO</th>
		</tr>";
	$no = 1;
	while ($d = mysql_fetch_array ($r)) {
				$suppliername = nama_supplier_saja($d[SUPPLIER]);
		$content .= "
		<tr>
			<td align='center'>$no</td>
			<td align='center'>$d[KODE_PR]</td>
			<td align='center'>$d[TGL_PR]</td>
			<td>$d[SUPPLIER]</td>
			<td>$suppliername</td>
			<td>$d[VALUE_PR]</td>
			<td>$d[PRINSIPAL]</td>
			<td>$d[KODE_PO]</td>
			<td>$d[TGL_PO]</td>
		</tr>";
		$no++;

	}
	$content .= "</table>";
		
		//laporan ke excel
		header("Content-Type: application/force-download");
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=\"Laporan_PR_Non_PO_$tanggal.xls\"");
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
