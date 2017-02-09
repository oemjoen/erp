<?php
include "../../inc/inc.koneksi.php";

// set timzone jakarta
date_default_timezone_set('Asia/Bangkok'); 

function kodecounter($prins){

	$sql	= "SELECT kodecounter FROM mstprinsipal WHERE `kodeprinsipal`='$prins'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kodecounter];
	}else{
		$hasil = 'hubungi pusat';
	}
	return $hasil;
}



$cabang1 	= $_POST['cabang1'];
$prins		= $_POST['prins'];
$countercek = date(m).date(Y);


$text		= "SELECT MAX(kodepo_beli) AS no_akhir FROM po_pembelian 
	WHERE MID(kodepo_beli,4,2) IN (SELECT kodecounter FROM mstprinsipal WHERE kodeprinsipal='$prins')
	AND  CONCAT(MID(kodepo_beli,14,2),RIGHT(kodepo_beli,4))='$countercek'";

$sql 		= mysql_query($text);
$row		= mysql_num_rows($sql);

//cek couter format nomber
$counter = kodecounter($prins);

if ($row>0){
	$data=mysql_fetch_array($sql);	
		//format kode beli BL0001
		//format kode beli SP/17008/SST/BLN/THN
		$no = $data['no_akhir'];
		$no_akhir = (int) substr($no, 5, 3);
		
		$no_akhir++;
		//membuat format kode beli
		$kode_beli = 'SP/'.$counter.sprintf("%03s",$no_akhir).'/SST/'.date(m).'/'.date(Y);
		
		$data['kode_retur']	= $kode_beli;
		
		echo json_encode($data);
}else{
	$data['kode_retur']	= 'SP/'.$counter.'001'.'/SST/'.date(m).'/'.date(Y);

	echo json_encode($data);
	
}


?>