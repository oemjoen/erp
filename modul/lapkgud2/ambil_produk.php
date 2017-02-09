<?php
include '../../inc/inc.koneksi.php';
$ambilcab = $_GET['ambilcab'];
$dataprod = mysql_query("SELECT DISTINCT Cabang,Produk,`Nama Produk` as nama, `Nama Prinsipal` as Prinsipal FROM dinventorysummary WHERE cabang='$ambilcab' ORDER BY `Nama Produk` ,`Produk`, `Prinsipal`;");
echo "-- Pilih Produk --";
while($k = mysql_fetch_array($dataprod)){
echo "<option value=\"".$k['Produk']."\">".$k['nama']." - ".$k['Produk']." - ".$k['Prinsipal']."</option>\n";
}
?>
