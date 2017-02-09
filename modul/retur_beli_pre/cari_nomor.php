<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kode	= $_POST['kode'];
$cabang1 = $_POST['cabang1'];
$tgl = $_POST['tgl'];
$noerdit = $_POST['noerdit'];

date_default_timezone_set('Asia/Jakarta'); 
$countercek = date(m).date(Y);

//$text	= "SELECT max(kode_beli) as no_akhir FROM retur_beli_pre where cabang='$cabang1'";
$text	= "SELECT MAX(kode_retur) AS no_akhir FROM retur_beli_pre WHERE cabang='$cabang1' AND CONCAT(MID(tgl_retur,6,2),LEFT(tgl_retur,4))='$countercek'";
$sql 	= mysql_query($text);
$row	= mysql_num_rows($sql);


if (empty($noerdit)){
		if ($row>0){
			$data=mysql_fetch_array($sql);	
				//format kode beli BL0001
				//format kode beli RR/PRE/CAB/001/TGL/BLN/THN
				$no = $data['no_akhir'];
				$no_akhir = (int) substr($no, 12, 3);
				
				$kode_tanggal=substr($tgl, 1, 2);
				$kode_bulan=substr($tgl, 4, 2);
				$kode_tahun=substr($tgl, 8, 4);
				
				
				$no_akhir++;
				//membuat format kode beli
				$kode_beli = 'RR/PRE/'.$cabang1.'/'.sprintf("%03s",$no_akhir).'/'.date(d).'/'.date(m).'/'.date(Y);
				
				$data['nomor']	= $kode_beli;
				$data['tglpredit']	= date(d)."-".date(m)."-".date(Y);
				
				echo json_encode($data);
		}else{
			$data['nomor']	= 'BL0001';

			echo json_encode($data);
			
		}
	}else {
				$data['nomor']	= $noerdit;
				$data['supp']  = supplier_retur_edit($noerdit);
				$data['kodepr']  = kode_pr_btb($noerdit);
				$data['kodepo']  = kode_po_btb($noerdit);
				$data['ekspedisi']  = ekspedisi_btb($noerdit);
				$data['suratjalan_btb']  = suratjalan_btb($noerdit);
				$data['kode_resi_btb']  = kode_resi_btb($noerdit);
				$data['tglpredit']  = jin_date_sql(tanggal_retur_edit($noerdit));
				$data['koli']  = koli_retur_edit($noerdit);
				echo json_encode($data);
	}
//echo $text;
?>