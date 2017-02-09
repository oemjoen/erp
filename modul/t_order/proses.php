<?php

include "../../inc/inc.koneksi.php";

$kode	= $_POST['kode'];
$id		= $_POST['id'];

$query	= "SELECT  NoSP as kode 
					FROM t_order 
					WHERE NoSP= '$kode'";
$sql 	= mysql_query($query);
$row	= mysql_num_rows($sql);
if ($row>0){
	$input = "UPDATE t_order SET Status='ProsesSP' WHERE NoSP= '$kode'";
	mysql_query($input);
	echo "<label id='info'>Data sukses diproses</label>";
}else{
	echo "<label id='info'>Maaf, Data tidak ada</label>";
}
//echo $query."<br>".$input;
include "tampil_data.php";

?>