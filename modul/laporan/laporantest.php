<?php
date_default_timezone_set('Asia/Jakarta'); 

include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';

$tanggal = date(d).date(m).date(Y);
$kode1	= jin_date_sql($_GET['kode1']);
$kode2	= jin_date_sql($_GET['kode2']);

	if (empty($kode1) && empty($kode2)){
	$q = "Select * from (SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`tglpo_beli`,'') AS tglpo_beli,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									HAVING kodepo_beli = ''
									UNION
									SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`tglpo_beli`,'') AS tglpo_beli,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian_pre a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian_pre po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									HAVING kodepo_beli = ''
									UNION
									SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`tglpo_beli`,'') AS tglpo_beli,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian_psi a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian_psi po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									HAVING kodepo_beli = '')allpr";
	}else{
	$q = "Select * from (SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`tglpo_beli`,'') AS tglpo_beli,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									HAVING kodepo_beli = ''
									UNION
									SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`tglpo_beli`,'') AS tglpo_beli,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian_pre a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian_pre po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									HAVING kodepo_beli = ''
									UNION
									SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`tglpo_beli`,'') AS tglpo_beli,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian_psi a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian_psi po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									HAVING kodepo_beli = '')allpr";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	$content = "<h3>LAPORAN SERVICE LEVEL PR BELUM ADA PO </h3><br/>
	<table width='390' border='1' style='border-collapse:collapse'>
		<tr>
				<th>No.</th>
				<th>Kode PR</th>
				<th>Tanggal Beli</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Kode Prinsipal</th>
				<th>Nama Prinsipal</th>
				<th>Kode PO</th>
				<th>Tanggal PO</th>
		</tr>";
	$no = 1;
	while ($d = mysql_fetch_array ($r)) {
		$content .= "
		<tr>
			<td align='center'>$no</td>
			<td align='center'>$d[kode_beli]</td>
			<td align='center'>$d[tgl_beli]</td>
			<td>$d[kode_supplier]</td>
			<td>$d[namasupplier]</td>
			<td>$d[kode_prinsipal]</td>
			<td>$d[namaprinsipal]</td>
			<td>$d[kodepo_beli]</td>
			<td>$d[tglpo_beli]</td>
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
