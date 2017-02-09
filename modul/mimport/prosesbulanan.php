<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";
 
 
error_reporting(E_ALL ^ E_NOTICE);
//seting di php.ini 
//ini_set('post_max_size',64M);
//ini_set('upload_max_filesize',64M);

require_once 'excel_reader2.php';


//echo "awallllll"; 
if( isset( $_POST['upload'] ) ){	
	// proses assigning baca data 
	$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
	//$data = new Spreadsheet_Excel_Reader("stokharian.xls");
	 
	//-------- import dari sheet 1 ----------
	// baca jumlah baris dalam sheet 1
	$jmlbaris = $data->rowcount(0);

	if ($jmlbaris > 0) {
			// delete data  tabel mstlogmonth
			$qDelete = "DELETE FROM `mstlogmonth`";
			mysql_query($qDelete);

			for ($i=2; $i<=$jmlbaris; $i++)
			{
			$cabangkode = cari_nama_cabang_upload_data(str_replace('\'', '"',$data->val($i, 2, 0)));
			//echo "1";
			// baca data pada baris ke-i, kolom ke-1, pada sheet 1
			$datakolom1 = str_replace('\'', '"',$data->val($i, 1, 0));
			$datakolom2 = str_replace('\'', '"',$data->val($i, 2, 0));
			$datakolom3 = str_replace('\'', '"',$data->val($i, 3, 0));
			$datakolom4 = str_replace('\'', '"',$data->val($i, 4, 0));
			$datakolom5 = str_replace('\'', '"',$data->val($i, 5, 0));
			$datakolom6 = str_replace('\'', '"',$data->val($i, 6, 0));
			$datakolom7 = str_replace('\'', '"',$data->val($i, 7, 0));
			$datakolom8 = str_replace('\'', '"',$data->val($i, 8, 0));
			$datakolom9 = $cabangkode;

			// insert data ke tabel mstlogday
			$query = "INSERT INTO `mstlogmonth`(`region`,`cabang`,`prinsipal`,`produk`,`kodeproduk`,`m1`,`m2`,`m3`,kodecabang) VALUES ('$datakolom1','$datakolom2','$datakolom3','$datakolom4','$datakolom5','$datakolom6','$datakolom7','$datakolom8','$datakolom9');";
			//echo $query."</br>";
			mysql_query($query)  or die("error in mysql_query");
			}
	 }

	$qDelete2 = "DELETE FROM `mstlogmonth` WHERE kodecabang='MDO' AND region='TIMUR'";
	$qDelete3 = "DELETE FROM `mstlogmonth` WHERE kodecabang='MKR' AND region='TIMUR'";
	$qDelete4 = "DELETE FROM `mstlogmonth` WHERE kodecabang='BJM' AND region='TENGAH'";
	$qDelete5 = "DELETE FROM `mstlogmonth` WHERE kodecabang='SMD' AND region='TENGAH'";

	mysql_query($qDelete2);
	mysql_query($qDelete3);
	mysql_query($qDelete4);
	mysql_query($qDelete5);
	
	echo "<p>Proses import selesai</p>";
	header('location: ../../media.php?module=import_data_bulanan');

}
else {
	echo "<p>Proses import Gagal</p>";
	header('location: ../../media.php?module=import_data_bulanan');
}
 
?>