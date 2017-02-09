<?php
include "../../inc/inc.koneksi.php";

$kode	= $_POST['kode'];
$id		= $_POST['id'];

$query	= "SELECT CONCAT(NoSP,NoOrder,KodeProduk) as kode 
					FROM t_order 
					WHERE CONCAT(NoSP,NoOrder,KodeProduk)= '$id'";
$sql 	= mysql_query($query);
$row	= mysql_num_rows($sql);
if ($row>0){
	$input = "DELETE FROM t_order  WHERE CONCAT(NoSP,NoOrder,KodeProduk)= '$id'";
	mysql_query($input);
	echo "<label id='info'>Data sukses dihapus</label>";
}else{
	echo "<label id='info'>Maaf, Data tidak ada</label>";
}
//echo $query."<br>".$input;
include "tampil_data.php";
?>