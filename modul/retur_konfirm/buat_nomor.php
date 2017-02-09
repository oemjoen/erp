<?php
include "../../inc/inc.koneksi.php";
$cabang1 	= $_POST['cabang1'];
$prins		= $_POST['prins'];
$kode	 = $_POST['kode'];

// set timzone jakarta
date_default_timezone_set('Asia/Bangkok'); 


//echo "SELECT `Kode Counter` AS kodecounter FROM mprinsipal WHERE `kode`='$prins'";
function kodecounter($prins){

	$sql	= "SELECT `Kode Counter` AS kodecounter,Prinsipal FROM mprinsipal WHERE `kode`='$prins'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kodecounter];
		$hasil2	= $data[Prinsipal];
	}else{
		$hasil = 'hubungi pusat (error Kode)';
		$hasil2 = 'hubungi pusat (error Kode)';
	}
	return array ($hasil,$hasil2);
}

function countercabang($kode){

	$sql	= "SELECT Kode as textcabang FROM mcabang WHERE `Cabang`='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[textcabang];
	}else{
		$hasil = 'hubungi pusat (error counter)';
	}
	return $hasil;
}




$countercek = date(m).date(Y);


/* $text		= "SELECT MAX(kodekonf_retur) AS no_akhir FROM konfirm_retur_beli 
	WHERE MID(kodekonf_retur,4,2) IN (SELECT kodecounter FROM mstprinsipal WHERE kodeprinsipal='$prins')
	AND  CONCAT(MID(kodekonf_retur,21,2),RIGHT(kodekonf_retur,4))='$countercek'";
 */
$text		= "SELECT MAX(no_retur) AS no_akhir FROM i_usulanreturbeli 
	WHERE MID(no_retur,5,2) IN (SELECT `Kode Counter` AS kodecounter FROM mprinsipal WHERE Kode='$prins')
	AND  CONCAT(MID(no_retur,22,2),RIGHT(no_retur,4))='$countercek'";
	
//echo "<br>".$text."<br>";

$sql 		= mysql_query($text);
$row		= mysql_num_rows($sql);


//cek couter format nomber
$counter = kodecounter($prins)[0];
$counterprins = kodecounter($prins)[1];
$cabangcabang = countercabang($cabang1);

if ($row>0){
	$data=mysql_fetch_array($sql);	

	
		//format kode beli BL0001
		//format kode beli SP/17008/SST/BLN/THN
		$no = $data['no_akhir'];
		$no_akhir = (int) substr($no, 7, 6);
		//echo 'cek : '.$no.' - 	'.$no_akhir; 
		$cabang_nomor = $data_cabang['kode_cabang'];
		
		$no_akhir++;
		//membuat format kode beli
		$kode_retur = 'URB/'.$counter.sprintf("%06s",$no_akhir).'/SST/'.$cabangcabang.'/'.date(m).'/'.date(Y);
		
		$data['kode_returkonfirm']	= $kode_retur;
		$data['counter_retur']	= $no_akhir;
		
		echo json_encode($data);
}else{
	$data['kode_returkonfirm']	= 'XXX/'.$counter.'000001'.'/SST/'.$cabangcabang.'/'.date(m).'/'.date(Y);
	$data['counter_retur']	= 1;

	echo json_encode($data);
	
}


?>