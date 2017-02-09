<?php
include '../../inc/inc.koneksi.php';
$ambilcab = $_GET['ambilcab'];
$sql = "SELECT DISTINCT Cabang,Produk,`Nama Produk` as nama, `Nama Prinsipal` as Prinsipal FROM dinventorysummary WHERE cabang='$ambilcab' ORDER BY `Prinsipal`,`Nama Produk` ,`Produk`;";
$dataprod = mysql_query($sql);
echo "-- Pilih Produk --";
// while($k = mysql_fetch_array($dataprod)){
	// $kode = $k['Produk'];
	//$nama = $r['nama_barang'];
	// echo "$kode\n";
	//echo $kode;
// }
?>
