<?php
include '../../inc/inc.koneksi.php';
$prod = $_GET['q'];
$ambilcab = $_GET['cabang'];


if($ambilcab = 'Pusat')
{
$sql = "SELECT DISTINCT Cabang,Produk,`Nama Produk` as nama, `Nama Prinsipal` as Prinsipal FROM dinventorysummary WHERE Produk like '%$prod%' OR `Nama Produk` like '%$prod%' OR `Nama Prinsipal` like '%$prod%' ORDER BY `Prinsipal`,`Nama Produk` ,`Produk`;";
}


$dataprod = mysql_query($sql);
 while($k = mysql_fetch_array($dataprod)){
	 $kode = $k['nama']." - ".$k['Produk'];
	 echo "$kode\n";
 }
?>
