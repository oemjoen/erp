<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";

$tgl	= jin_date_sql($_POST["tgl"]);

$sql	= mysql_query("SELECT distinct(kode_beli) as kode_beli,cabang FROM pembelian 
								where kode_beli not in (select distinct kode_beli from po_pembelian) 
									AND tgl_beli='$tgl'");
while($r=mysql_fetch_array($sql)){
	$kode = $r['kode_beli'];
	$cabang = $r['cabang'];
	echo "<option value='$kode'>$kode - $cabang</option>";
}
?>