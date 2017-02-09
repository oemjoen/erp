<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';

$kode1	= jin_date_sql($_GET['kode1']);
$kode2	= jin_date_sql($_GET['kode2']);
$cabang	= $_GET['cabang'];

if ($cabang == 'KPS'){$where2="";}
else{$where2="cabang='$cabang' AND ";}

	if (empty($kode1) && empty($kode2)){
	$q = "SELECT * FROM (
				SELECT a.kode_beli AS `kode_beli`,
						a.tgl_beli AS `tgl_beli`,
						IFNULL(gab.`kodepo_beli`,'') AS `kodepo_beli`, 
						IFNULL(gab.`tglpo_beli`,'') AS `tglpo_beli`,
						a.kode_supplier AS `kode_supplier`,
						b.`namasupplier` AS `namasupplier`,
						IFNULL(gab.`kode_prinsipal`,'') AS `kode_prinsipal`,
						IFNULL(gab.`namaprinsipal`,'') AS `namaprinsipal`,
						a.`kode_barang` AS `kode_barang`,c.`namaproduk` AS `namaproduk`,a.`jumlah_beli` AS `jumlah_beli`,
						IFNULL(gab.jumlah_beli_valid,'') AS `jumlah_beli_valid`,ket_pusat,a.cabang
						FROM pembelian a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN (
								SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal`,kode_barang,jumlah_beli_valid ,ket_pusat FROM po_pembelian po
								LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
							) gab ON a.`kode_beli`= gab.kode_beli AND a.`kode_barang`=gab.kode_barang
						LEFT JOIN mstproduk c ON a.`kode_barang`=c.`kodeproduk`
				UNION
				SELECT a.kode_beli AS `kode_beli`,
						a.tgl_beli AS `tgl_beli`,
						IFNULL(gab.`kodepo_beli`,'') AS `kodepo_beli`, 
						IFNULL(gab.`tglpo_beli`,'') AS `tglpo_beli`,
						a.kode_supplier AS `kode_supplier`,
						b.`namasupplier` AS `namasupplier`,
						IFNULL(gab.`kode_prinsipal`,'') AS `kode_prinsipal`,
						IFNULL(gab.`namaprinsipal`,'') AS `namaprinsipal`,
						a.`kode_barang` AS `kode_barang`,c.`namaproduk` AS `namaproduk`,a.`jumlah_beli` AS `jumlah_beli`,
						IFNULL(gab.jumlah_beli_valid,'') AS `jumlah_beli_valid`,ket_pusat,a.cabang
						FROM pembelian_psi a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN (
								SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal`,kode_barang,jumlah_beli_valid ,ket_pusat FROM po_pembelian_psi po
								LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
							) gab ON a.`kode_beli`= gab.kode_beli AND a.`kode_barang`=gab.kode_barang
						LEFT JOIN mstproduk c ON a.`kode_barang`=c.`kodeproduk`
				UNION
				SELECT a.kode_beli AS `kode_beli`,
						a.tgl_beli AS `tgl_beli`,
						IFNULL(gab.`kodepo_beli`,'') AS `kodepo_beli`, 
						IFNULL(gab.`tglpo_beli`,'') AS `tglpo_beli`,
						a.kode_supplier AS `kode_supplier`,
						b.`namasupplier` AS `namasupplier`,
						IFNULL(gab.`kode_prinsipal`,'') AS `kode_prinsipal`,
						IFNULL(gab.`namaprinsipal`,'') AS `namaprinsipal`,
						a.`kode_barang` AS `kode_barang`,c.`namaproduk` AS `namaproduk`,a.`jumlah_beli` AS `jumlah_beli`,
						IFNULL(gab.jumlah_beli_valid,'') AS `jumlah_beli_valid`,ket_pusat,a.cabang
						FROM pembelian_pre a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN (
								SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal`,kode_barang,jumlah_beli_valid ,ket_pusat FROM po_pembelian_pre po
								LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
							) gab ON a.`kode_beli`= gab.kode_beli AND a.`kode_barang`=gab.kode_barang
						LEFT JOIN mstproduk c ON a.`kode_barang`=c.`kodeproduk`
				)gab				
				ORDER BY `tgl_beli` DESC,kode_beli";
	}else{
	$q = "SELECT * FROM (
				SELECT a.kode_beli AS `kode_beli`,
						a.tgl_beli AS `tgl_beli`,
						IFNULL(gab.`kodepo_beli`,'') AS `kodepo_beli`, 
						IFNULL(gab.`tglpo_beli`,'') AS `tglpo_beli`,
						a.kode_supplier AS `kode_supplier`,
						b.`namasupplier` AS `namasupplier`,
						IFNULL(gab.`kode_prinsipal`,'') AS `kode_prinsipal`,
						IFNULL(gab.`namaprinsipal`,'') AS `namaprinsipal`,
						a.`kode_barang` AS `kode_barang`,c.`namaproduk` AS `namaproduk`,a.`jumlah_beli` AS `jumlah_beli`,
						IFNULL(gab.jumlah_beli_valid,'') AS `jumlah_beli_valid`,ket_pusat,a.cabang
						FROM pembelian a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN (
								SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal`,kode_barang,jumlah_beli_valid ,ket_pusat FROM po_pembelian po
								LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
							) gab ON a.`kode_beli`= gab.kode_beli AND a.`kode_barang`=gab.kode_barang
						LEFT JOIN mstproduk c ON a.`kode_barang`=c.`kodeproduk`
				UNION
				SELECT a.kode_beli AS `kode_beli`,
						a.tgl_beli AS `tgl_beli`,
						IFNULL(gab.`kodepo_beli`,'') AS `kodepo_beli`, 
						IFNULL(gab.`tglpo_beli`,'') AS `tglpo_beli`,
						a.kode_supplier AS `kode_supplier`,
						b.`namasupplier` AS `namasupplier`,
						IFNULL(gab.`kode_prinsipal`,'') AS `kode_prinsipal`,
						IFNULL(gab.`namaprinsipal`,'') AS `namaprinsipal`,
						a.`kode_barang` AS `kode_barang`,c.`namaproduk` AS `namaproduk`,a.`jumlah_beli` AS `jumlah_beli`,
						IFNULL(gab.jumlah_beli_valid,'') AS `jumlah_beli_valid`,ket_pusat,a.cabang
						FROM pembelian_psi a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN (
								SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal`,kode_barang,jumlah_beli_valid ,ket_pusat FROM po_pembelian_psi po
								LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
							) gab ON a.`kode_beli`= gab.kode_beli AND a.`kode_barang`=gab.kode_barang
						LEFT JOIN mstproduk c ON a.`kode_barang`=c.`kodeproduk`
				UNION
				SELECT a.kode_beli AS `kode_beli`,
						a.tgl_beli AS `tgl_beli`,
						IFNULL(gab.`kodepo_beli`,'') AS `kodepo_beli`, 
						IFNULL(gab.`tglpo_beli`,'') AS `tglpo_beli`,
						a.kode_supplier AS `kode_supplier`,
						b.`namasupplier` AS `namasupplier`,
						IFNULL(gab.`kode_prinsipal`,'') AS `kode_prinsipal`,
						IFNULL(gab.`namaprinsipal`,'') AS `namaprinsipal`,
						a.`kode_barang` AS `kode_barang`,c.`namaproduk` AS `namaproduk`,a.`jumlah_beli` AS `jumlah_beli`,
						IFNULL(gab.jumlah_beli_valid,'') AS `jumlah_beli_valid`,ket_pusat,a.cabang
						FROM pembelian_pre a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN (
								SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,kode_prinsipal,`namaprinsipal`,kode_barang,jumlah_beli_valid ,ket_pusat FROM po_pembelian_pre po
								LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
							) gab ON a.`kode_beli`= gab.kode_beli AND a.`kode_barang`=gab.kode_barang
						LEFT JOIN mstproduk c ON a.`kode_barang`=c.`kodeproduk`
				)gab
				WHERE $where2 tgl_beli BETWEEN '$kode1' AND '$kode2'";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	$content = "<h3>LAPORAN SERVICE LEVEL PR - PO (dari Tanggal $kode1 sampai dengan $kode2)</h3><br/>
	<table width='390' border='1' style='border-collapse:collapse'>
		<tr>
			<th>No</th>
			<th>KODE PR</th>
			<th>TANGGAL PR</th>
			<th>KODE PO</th>
			<th>TANGGAL PO</th>
			<th>KODE SUPPLIER</th>
			<th>NAMA SUPPLIER</th>
			<th>KODE PRINSIPAL</th>
			<th>NAMA PRINSIPAL</th>
			<th>KODE BARANG</th>
			<th>NAMA BARANG</th>
			<th>JUMLAH PR</th>
			<th>JUMLAH PO</th>
			<th>KETERANGAN PUSAT</th>
		</tr>";
	$no = 1;
	while ($d = mysql_fetch_array ($r)) {
		$content .= "
		<tr>
			<td align='center'>$no</td>
			<td>$d[kode_beli]</td>
			<td>$d[tgl_beli]</td>
			<td>$d[kodepo_beli]</td>
			<td>$d[tglpo_beli]</td>
			<td>$d[kode_supplier]</td>
			<td>$d[namasupplier]</td>
			<td>$d[kode_prinsipal]</td>
			<td>$d[namaprinsipal]</td>
			<td>$d[kode_barang]</td>
			<td>$d[namaproduk]</td>
			<td>$d[jumlah_beli]</td>
			<td>$d[jumlah_beli_valid]</td>
			<td>$d[ket_pusat]</td>
		</tr>";
		$no++;

	}
	$content .= "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=lap_service_level.xls");
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
