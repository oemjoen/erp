<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";

$table		="retur_beli_pre";

$koderetur				=$_POST[koderetur];
$kode_supplier			=$_POST[supplier];
$tgl					=jin_date_sql($_POST[tgl]);
$koli					=$_POST[koli];

// batch,terima,ed 1
$kode_brg				=$_POST[kode_brg];
$satuan					=$_POST[satuan];
$jmlterima				=$_POST[jmlterima];
$batchnumber			=$_POST[batchnumber];
$ed						=jin_date_sql($_POST[ed]);
$refdopst				=$_POST[refdopst];
$tglrefdopst			=jin_date_sql($_POST[tglrefdopst]);
$alasan					=$_POST[alasan];
$dari					=$_POST[dari];


$cabang					=$_POST[cabang];
$username				=$_POST[username];
$tglbuat				= date("Y-m-d H:i:s"); 
$tglbtb					= date("Y-m-d"); 



$query ="SELECT * FROM $table WHERE kode_retur='$koderetur' AND kode_supplier='$kode_supplier' AND kode_barang='$kode_brg'  AND `batch` = '$batchnumber'";
$sql = mysql_query($query);
$row	= mysql_num_rows($sql);
if ($row>0){
	$input	= "UPDATE `retur_beli_pre`
					SET 
					  `jumlah_retur` = '$jmlterima',
					  `batch` = '$batchnumber',
					  `ed` = '$ed',
					  `do_pusat` = '$refdopst',
					  `tgl_do_pusat` = '$tglrefdopst',
					  `asal_retur` = '$dari',
					  `alasan_retur` = '$alasan',
					  `user_edit` = '$username',
					  `tgl_edit` = '$tglbuat'
					WHERE `kode_retur` = '$koderetur'
						AND `kode_supplier` = '$kode_supplier'
						AND `kode_barang` = '$kode_brg' 
						AND `batch` = '$batchnumber'";
	mysql_query($input);
	echo "<label id='info'><b>Data Sukses diubah</b></label>";
}else{
	$input = "INSERT INTO `retur_beli_pre`
							(`kode_retur`,
							 `tgl_retur`,
							 `kode_supplier`,
							 `kode_barang`,
							 `satuan`,
							 `jumlah_retur`,
							 `batch`,
							 `ed`,
							 `harga_retur`,
							 `cabang`,
							 `do_pusat`,
							 `tgl_do_pusat`,
							 `asal_retur`,
							 `alasan_retur`,
							 `jum_koli`,
							 `user_buat`,
							 `tgl_buat`)
				VALUES ('$koderetur',
						'$tgl',
						'$kode_supplier',
						'$kode_brg',
						'$satuan',
						'$jmlterima',
						'$batchnumber',
						'$ed',
						0,
						'$cabang',
						'$refdopst',
						'$tglrefdopst',
						'$dari',
						'$alasan',
						'$koli',
						'$username',
						'$tglbuat')";
	mysql_query($input);
	echo "<label id='info'><b>Data sukses disimpan</b></label>";
}	
//echo "<br/>".$query."<br/>" ;
//echo "<br/>".$input."<br/>" ;
include "tampil_data.php";

?>