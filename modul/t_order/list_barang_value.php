<?php
include "../../inc/inc.koneksi.php";

$q	= $_GET["q"];
if (!$q) return;

$sql	= mysql_query("SELECT a.`Kode Produk` AS kodeproduk,a.`Produk` AS namaproduk,a.`Satuan` FROM mproduk a WHERE (a.`Kode Produk` LIKE '%$q%' OR a.Produk LIKE '%$q%') AND `Kode SMS` is not null ");
while($r=mysql_fetch_array($sql)){
	$kode = $r['kodeproduk'];
	//$nama = $r['nama_barang'];
	echo "$kode\n";
	//echo $kode;
}
?>