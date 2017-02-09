<?php
include "../../inc/inc.koneksi.php";

$kode	= $_POST['kode'];
$id		= $_POST['id'];
$koderetur				=$_POST[koderetur];

$query	= "SELECT CONCAT(kode_retur,kode_supplier,kode_barang,batch) AS kode 
	FROM retur_beli_pre WHERE CONCAT(kode_retur,kode_supplier,kode_barang,batch)= '$id'";
$sql 	= mysql_query($query);
$row	= mysql_num_rows($sql);
if ($row>0){
	$input = "DELETE FROM retur_beli_pre WHERE CONCAT(kode_retur,kode_supplier,kode_barang,batch)= '$id'";
	mysql_query($input);
	echo "<label id='info'>Data sukses dihapus</label>";
}else{
	echo "<label id='info'>Maaf, tidak ada</label>";
}
//echo $query."<br>".$input;
include "tampil_data.php";
?>