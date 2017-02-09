<?php

	
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
date_default_timezone_set('Asia/Bangkok');


$table		="i_usulanreturbeli";

$tglbuat			=date('Y-m-d H:i:s');
$prinsipal			=$_POST[prins];
$supplier			=$_POST[kode_supplier];
$cabang				=$_POST[cabang];
$no_retur			=$_POST[kodekonf_retur];
$counterr			=$_POST[counterr];
//$tgl				=jin_date_sql($_POST[tgl]);
$tgl				=date('Y-m-d');
$no_acu				=$_POST[kode];
$tgl_acu			=$_POST[tglacu];
$kode_produk		=$_POST[kodep];
$qtybpb				=$_POST[qtybpb];
$Bonus				=$_POST[Bonus];
$batch				=$_POST[batch];
$exp_date			=$_POST[expd];
$Satuan				=$_POST[Satuan];
$qty_retur			=$_POST[jmlvalid];
$qty_bns_retur		=$_POST[jmlvalidbns];
$ket_pusat			=$_POST[ket_pusat];
$hpc				=$_POST[hpc];
$disc_cab			=$_POST[disc];
$hrg_cab			=$_POST[harga];
$gros_cab			=$_POST[grosb];
$pot_cab			=$_POST[pot];
$value_cab			=$_POST[val];
$ppn_cab			=$_POST[ppn];
$total_cab			=$_POST[nilju];
$hpp				=$_POST[hpp];
$disc_pst			=$_POST[discp];
$hrg_pst			=$_POST[hrgp];
$gros_pst			=$_POST[grBPP];
$value_pst			=$_POST[valp];
$pot_pst			=$_POST[potp];
$total_pst			=$_POST[niljup];
$user_tambah		=$_POST[kodeuser];
$ppn_pst			=$value_pst * 0.1;


$query ="SELECT * FROM $table 
			WHERE no_retur= '$no_retur' and no_acu= '$no_acu' and kode_produk='$kode_produk' and batch='$batch'
		";
$sql = mysql_query($query);
$row	= mysql_num_rows($sql);
if ($row>0){
	$input	= "UPDATE `erp`.`i_usulanreturbeli`
					SET `cabang` = '$cabang',`tgl_acu` = '$tgl_acu',`no_acu` = '$no_acu',
						`prinsipal` = '$prinsipal',`supplier` = '$supplier',
						`kode_produk` = '$kode_produk',`exp_date` = '$exp_date',`batch` = '$batch',
						`qty_retur` = '$qty_retur',`qty_bns_retur` = '$qty_bns_retur',
						`disc_cab` = '$disc_cab',`disc_pst` = '$disc_pst',
						`hrg_cab` = '$hrg_cab',`hrg_pst` = '$hrg_pst',`hpc` = '$hpc',`hpp` = '$hpp',
						`gros_cab` = '$gros_cab',`pot_cab` = '$pot_cab',`value_cab` = '$value_cab',
						`ppn_cab` = '$ppn_cab',`total_cab` = '$total_cab',
						`gros_pst` = '$gros_pst',`pot_pst` = '$pot_pst',`value_pst` = '$value_pst',
						`ppn_pst` = '$ppn_pst',`total_pst` = '$total_pst',
						`user_edit` = '$user_tambah',`waktu_edit` = '$tglbuat'
					WHERE `cabang` = '$cabang' AND `no_retur` = '$no_retur' AND `tgl_acu` = '$tgl_acu' AND `no_acu` = '$no_acu' 
						AND `kode_produk` = '$kode_produk' AND `exp_date` = '$exp_date' AND `batch` = '$batch'";
	mysql_query($input);
		//echo $input;
		//echo $query;								
	echo "<label id='info'><b>Data Sukses diubah</b></label>";
}else{
	$input = "INSERT INTO `erp`.`i_usulanreturbeli`
						(`cabang`,`tgl_retur`,`no_retur`,`tgl_acu`,`no_acu`,`kode_produk`,`exp_date`,`batch`,
						`qty_retur`,`qty_bns_retur`,`disc_cab`,`disc_pst`,`hrg_cab`,`hrg_pst`,`hpc`,`hpp`,
						`gros_cab`,`pot_cab`,`value_cab`,`ppn_cab`,`total_cab`,
						`gros_pst`,`pot_pst`,`value_pst`,`ppn_pst`,`total_pst`,`status`,`counter_usulan`,
						`user_tambah`,`waktu_tambah`,`waktu_edit`,prinsipal,supplier)
				VALUES ('$cabang','$tgl','$no_retur','$tgl_acu','$no_acu','$kode_produk','$exp_date','$batch',
						'$qty_retur','$qty_bns_retur','$disc_cab','$disc_pst','$hrg_cab','$hrg_pst','$hpc','$hpp',
						'$gros_cab','$pot_cab','$value_cab','$ppn_cab','$total_cab',
						'$gros_pst','$pot_pst','$value_pst',$ppn_pst,'$total_pst','Usulan Retur','$counterr',
						'$user_tambah','$tglbuat','$tglbuat','$prinsipal','$supplier')";
	mysql_query($input);
		//echo $input;
		//echo $query;
	echo "<label id='info'><b>Data sukses disimpan</b></label>";
}	

//backhome("home");

//echo $query."<br><br>".$input."<br><br>";
//include "tampil_data.php";

?>