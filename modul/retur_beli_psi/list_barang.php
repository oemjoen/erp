<?php
include "../../inc/inc.koneksi.php";

$q	= $_GET["q"];
if (!$q) return;

$sql	= mysql_query("SELECT * FROM mstproduk a  
							left join mstprinsipal b on a.kodeprinsipal=b.kodeprinsipal
							WHERE (a.kodeproduk like '%$q%' OR a.namaproduk like '%$q%' OR b.namaprinsipal like '%$q%') AND a.kategorikhusus='04'") ;
while($r=mysql_fetch_array($sql)){
	$kode = $r['kodeproduk'];
	//$nama = $r['nama_barang'];
	echo "$kode\n";
	//echo $kode;
}
?>