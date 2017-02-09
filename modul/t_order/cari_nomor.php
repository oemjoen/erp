<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kode	= $_POST['kode'];
$cabang1 = $_POST['cabang1'];
$tgl = $_POST['tgl'];
$noerdit = $_POST['noerdit'];
$kap = $_POST['kap'];

date_default_timezone_set('Asia/Jakarta'); 
$countercek = date(m).date(Y);

//$text	= "SELECT max(kode_beli) as no_akhir FROM pembelian where cabang='$cabang1'";
$text	= "SELECT MAX(RIGHT(NoOrder,5)) AS no_akhir FROM t_order WHERE cabang='$cabang1' AND KodeApotik='$kap'";
$sql 	= mysql_query($text);
$row	= mysql_num_rows($sql);


if (empty($noerdit)){
		//if ($row>0){
			$data=mysql_fetch_array($sql);	
				//format kode beli BL0001
				//format kode beli SP/CAB/001/TGL/BLN/THN
				$no = $data['no_akhir'];
				$no_akhir = (int)$no;
			
				
				$no_akhir++;
				//membuat format kode beli
				//$kode_beli = 'MO/'.$cabang1.'/'.sprintf("%03s",$no_akhir).'/'.date(d).'/'.date(m).'/'.date(Y);
				
				$data['nomor']	= 'ORD/'.$kap.'/'.sprintf("%05s",$no_akhir);
				$data['tglpredit']	= date(d)."-".date(m)."-".date(Y);
				
				echo json_encode($data);
		// }else{
			// $data['nomor']	= 'MO/'.$cabang1.'/001/'.date(d).'/'.date(m).'/'.date(Y);;

			// echo json_encode($data);
			
		// }
	}else {
				$sqledit = "SELECT DISTINCT TglSP as tgl from t_order WHERE NoSP='$noerdit'";
				$dataedit = mysql_fetch_array($sqledit);
				$data['nomor']	= $noerdit;
				$data['tglpredit']  = date(d)."-".date(m)."-".date(Y);
				//$data['tglpredit']  = jin_date_sql($dataedit['tgl']);
			//	$data['tglpredit']  = $sqledit;
				echo json_encode($data);
	}
?>