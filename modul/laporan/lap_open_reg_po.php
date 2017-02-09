<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';

$kode1	= "";
$kode2	= "";

	if (empty($kode1) && empty($kode2)){
	$q = "SELECT   b.`namaprinsipal` AS namaprinsipal,
			  c.`namasupplier` AS namasupplier,
			  a.`cabang` AS `cabang`,
			  a.`kodepo_beli` AS `kodepo_beli`,
			  a.`tglpo_beli` AS `tglpo_beli`,
			  a.`kode_beli` AS `kode_beli`,
			  d.`namaproduk` AS `namaproduk`,
			  a.`kode_barang` AS `kode_barang`,
			  a.`jumlah_beli_valid` AS `jumlah_beli_valid`,
			  a.`reg_tgl_kirim_pr` AS `reg_tgl_kirim_pr`,
			  a.`reg_tgl_kirim_po` AS `reg_tgl_kirim_po`,
			  a.`reg_tgl_kirim_barang` AS `reg_tgl_kirim_barang`,
			  a.`reg_tgl_terima_barang` AS `reg_tgl_terima_barang`,
			  e.`status_regpo` AS `status_regpo`,
			  f.`statuspo_open` AS `statuspo_open`
			FROM (
			SELECT kode_prinsipal,kode_supplier,reg_status,reg_openclose_po,cabang,kodepo_beli,tglpo_beli,kode_beli,kode_barang,jumlah_beli_valid,reg_tgl_kirim_pr,reg_tgl_kirim_po,reg_tgl_kirim_barang,reg_tgl_terima_barang FROM `po_pembelian` 
			WHERE `jumlah_beli_valid`>0 AND (`reg_tgl_kirim_pr` <> '0000-00-00' OR `reg_tgl_kirim_po` <> '0000-00-00' OR `reg_tgl_kirim_barang` <> '0000-00-00' OR `reg_tgl_terima_barang` <> '0000-00-00')
			UNION
			SELECT kode_prinsipal,kode_supplier,reg_status,reg_openclose_po,cabang,kodepo_beli,tglpo_beli,kode_beli,kode_barang,jumlah_beli_valid,reg_tgl_kirim_pr,reg_tgl_kirim_po,reg_tgl_kirim_barang,reg_tgl_terima_barang FROM `po_pembelian_pre`
			WHERE `jumlah_beli_valid`>0 AND (`reg_tgl_kirim_pr` <> '0000-00-00' OR `reg_tgl_kirim_po` <> '0000-00-00' OR `reg_tgl_kirim_barang` <> '0000-00-00' OR `reg_tgl_terima_barang` <> '0000-00-00')
			UNION 
			SELECT kode_prinsipal,kode_supplier,reg_status,reg_openclose_po,cabang,kodepo_beli,tglpo_beli,kode_beli,kode_barang,jumlah_beli_valid,reg_tgl_kirim_pr,reg_tgl_kirim_po,reg_tgl_kirim_barang,reg_tgl_terima_barang FROM `po_pembelian`_psi
			WHERE `jumlah_beli_valid`>0 AND (`reg_tgl_kirim_pr` <> '0000-00-00' OR `reg_tgl_kirim_po` <> '0000-00-00' OR `reg_tgl_kirim_barang` <> '0000-00-00' OR `reg_tgl_terima_barang` <> '0000-00-00')			)a
			LEFT JOIN mstprinsipal b ON a.`kode_prinsipal`=b.`kodeprinsipal`
			LEFT JOIN `mstsupplier2` c ON a.`kode_supplier`=c.`kodesupplier` 
			LEFT JOIN `mstproduk` d ON a.`kode_barang`=d.`kodeproduk`
			LEFT JOIN `mststatus_refpo` e ON a.`reg_status`=e.`kodestatus`
			LEFT JOIN `mststat_openpo` f ON a.`reg_openclose_po`=f.`kode_statpo_open`
			WHERE `statuspo_open` <> 'CLOSED'
			ORDER BY b.`namaprinsipal`,  c.`namasupplier`,a.`cabang`,  a.`kodepo_beli`,a.`kode_barang`";
	}else{
	$q = "SELECT    b.`namaprinsipal` AS namaprinsipal,
			  c.`namasupplier` AS namasupplier,
			  a.`cabang` AS `cabang`,
			  a.`kodepo_beli` AS `kodepo_beli`,
			  a.`tglpo_beli` AS `tglpo_beli`,
			  a.`kode_beli` AS `kode_beli`,
			  d.`namaproduk` AS `namaproduk`,
			  a.`kode_barang` AS `kode_barang`,
			  a.`jumlah_beli_valid` AS `jumlah_beli_valid`,
			  a.`reg_tgl_kirim_pr` AS `reg_tgl_kirim_pr`,
			  a.`reg_tgl_kirim_po` AS `reg_tgl_kirim_po`,
			  a.`reg_tgl_kirim_barang` AS `reg_tgl_kirim_barang`,
			  a.`reg_tgl_terima_barang` AS `reg_tgl_terima_barang`,
			  e.`status_regpo` AS `status_regpo`,
			  f.`statuspo_open` AS `statuspo_open`
			FROM (
				SELECT kode_prinsipal,kode_supplier,reg_status,reg_openclose_po,cabang,kodepo_beli,tglpo_beli,kode_beli,kode_barang,jumlah_beli_valid,reg_tgl_kirim_pr,reg_tgl_kirim_po,reg_tgl_kirim_barang,reg_tgl_terima_barang FROM `po_pembelian` 
				WHERE `jumlah_beli_valid`>0 AND (`reg_tgl_kirim_pr` <> '0000-00-00' OR `reg_tgl_kirim_po` <> '0000-00-00' OR `reg_tgl_kirim_barang` <> '0000-00-00' OR `reg_tgl_terima_barang` <> '0000-00-00')
				UNION
				SELECT kode_prinsipal,kode_supplier,reg_status,reg_openclose_po,cabang,kodepo_beli,tglpo_beli,kode_beli,kode_barang,jumlah_beli_valid,reg_tgl_kirim_pr,reg_tgl_kirim_po,reg_tgl_kirim_barang,reg_tgl_terima_barang FROM `po_pembelian_pre`
				WHERE `jumlah_beli_valid`>0 AND (`reg_tgl_kirim_pr` <> '0000-00-00' OR `reg_tgl_kirim_po` <> '0000-00-00' OR `reg_tgl_kirim_barang` <> '0000-00-00' OR `reg_tgl_terima_barang` <> '0000-00-00')
				UNION 
				SELECT kode_prinsipal,kode_supplier,reg_status,reg_openclose_po,cabang,kodepo_beli,tglpo_beli,kode_beli,kode_barang,jumlah_beli_valid,reg_tgl_kirim_pr,reg_tgl_kirim_po,reg_tgl_kirim_barang,reg_tgl_terima_barang FROM `po_pembelian`_psi
				WHERE `jumlah_beli_valid`>0 AND (`reg_tgl_kirim_pr` <> '0000-00-00' OR `reg_tgl_kirim_po` <> '0000-00-00' OR `reg_tgl_kirim_barang` <> '0000-00-00' OR `reg_tgl_terima_barang` <> '0000-00-00')			
				)a
			LEFT JOIN mstprinsipal b ON a.`kode_prinsipal`=b.`kodeprinsipal`
			LEFT JOIN `mstsupplier2` c ON a.`kode_supplier`=c.`kodesupplier` 
			LEFT JOIN `mstproduk` d ON a.`kode_barang`=d.`kodeproduk`
			LEFT JOIN `mststatus_refpo` e ON a.`reg_status`=e.`kodestatus`
			LEFT JOIN `mststat_openpo` f ON a.`reg_openclose_po`=f.`kode_statpo_open`
			WHERE `statuspo_open` <> 'CLOSED'
			ORDER BY b.`namaprinsipal`,  c.`namasupplier`,a.`cabang`,  a.`kodepo_beli`,a.`kode_barang`";		
	}
		
	$r = mysql_query($q);
	$tgl = date('d M Y');
	$content = "<h3>LAPORAN SERVICE LEVEL PR BELUM ADA PO </h3><br/>
	<table width='390' border='1' style='border-collapse:collapse'>
		<tr>
				<th>No.</th>
				<th>NAMA PRINSIPAL</th>
				<th>NAMA SUPPLIER</th>
				<th>CABANG</th>
				<th>KODE PO</th>
				<th>TANGGAL PO</th>
				<th>KODE BELI</th>
				<th>NAMA PRODUK</th>
				<th>KODE PRODUK</th>
				<th>JUMLAH PO</th>
				<th>TANGGAL KIRIM PR</th>
				<th>TANGGAL KIRIM PO</th>
				<th>TANGGAL KIRIM BARANG</th>
				<th>TANGGAL TERIMA BARANG</th>				
				<th>STATUS PO</th>
				<th>OPEN/CLOSED</th>
		</tr>";
	$no = 1;
	while ($d = mysql_fetch_array ($r)) {
		$content .= "
		<tr>
			<td align='center'>$no</td>
			<td>$d[namaprinsipal]</td>
			<td>$d[namasupplier]</td>
			<td>$d[cabang]</td>
			<td>$d[kodepo_beli]</td>
			<td>$d[tglpo_beli]</td>
			<td>$d[kode_beli]</td>
			<td>$d[namaproduk]</td>
			<td>$d[kode_barang]</td>
			<td>$d[jumlah_beli_valid]</td>
			<td>$d[reg_tgl_kirim_pr]</td>
			<td>$d[reg_tgl_kirim_po]</td>
			<td>$d[reg_tgl_kirim_barang]</td>
			<td>$d[reg_tgl_terima_barang]</td>
			<td>$d[status_regpo]</td>
			<td>$d[statuspo_open]</td>
		</tr>";
		$no++;

	}
	$content .= "</table>";
		
		//laporan ke excel
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=laporan_register_po_open.xls");
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
