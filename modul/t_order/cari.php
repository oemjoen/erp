<?php
include "../../inc/inc.koneksi.php";
$kode	= $_POST['kode'];
$text	= "SELECT *
			FROM t_order WHERE CONCAT(NoSP,NoOrder,KodeProduk)= '$kode'";
$sql 	= mysql_query($text);
$row	= mysql_num_rows($sql);
if ($row>0){
while ($r=mysql_fetch_array($sql)){	
	
	$data['Kode']           = $kode;
	$data['NoOrder']         = $r[NoOrder];
	$data['KodeProduk']     = $r[KodeProduk];
	$data['Qty']           	= $r[Qty];
	$data['Bonus']			= $r[Bonus];
	$data['Diskon']	       	= $r[Diskon];
	$data['Harga']	    	= $r[Harga];
	$data['Gross']	    	= $r[Gross];
	$data['Potongan']	    = $r[Potongan];
	$data['Value']	    	= $r[Value];
	$data['Ppn']	    	= $r[Ppn];
	$data['Total']	    	= $r[Total];
	
	echo json_encode($data);
}
}else{
	$data['kode_barang']	= '';
	$data['nama_barang']	= '';
	$data['satuan']			= '';
	$data['harga_beli']		= '';
	$data['harga_jual']		= '';
	$data['stok_awal']		= '';

	echo json_encode($data);
	
}

?>