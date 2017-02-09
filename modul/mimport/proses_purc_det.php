<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";
 
$tablename = "purchase_detail_report";
$deleterecords = "TRUNCATE TABLE $tablename"; //empty the table of its current records
mysql_query($deleterecords);

//echo "awallllll"; 
if( isset( $_POST['submit'] ) ){	
	//if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
		//echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
		//echo "<h2>Displaying contents:</h2>";
		//readfile($_FILES['filename']['tmp_name']);
	//}

	//Import uploaded file to Database
	$handle = fopen($_FILES['filename']['tmp_name'], "r");
	//fgetcsv($handle, 1000, ","); 
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$datakolom0 = str_replace('\'', '"',$data[0]);		
			$datakolom1 = str_replace('\'', '"',$data[1]);
			$datakolom2 = str_replace('\'', '"',$data[2]);
			$datakolom3 = str_replace('\'', '"',$data[3]);
			$datakolom4 = str_replace('\'', '"',$data[4]);
			$datakolom5 = str_replace('\'', '"',$data[5]);
			$datakolom6 = str_replace('\'', '"',$data[6]);
			$datakolom7 = str_replace('\'', '"',$data[7]);
			$datakolom8 = str_replace('\'', '"',$data[8]);
			$datakolom9 = str_replace('\'', '"',$data[9]);
			$datakolom10 = str_replace('\'', '"',$data[10]);
			$datakolom11 = str_replace('\'', '"',$data[11]);
			$datakolom12 = str_replace('\'', '"',$data[12]);
			$datakolom13 = str_replace('\'', '"',$data[13]);
			$datakolom14 = str_replace('\'', '"',$data[14]);
			$datakolom15 = str_replace('\'', '"',$data[15]);
			$datakolom16 = str_replace('\'', '"',$data[16]);
			$datakolom17 = str_replace('\'', '"',$data[17]);
			$datakolom18 = str_replace('\'', '"',$data[18]);
			$datakolom19 = str_replace('\'', '"',$data[19]);
			$datakolom20 = str_replace('\'', '"',$data[20]);
			$datakolom21 = str_replace('\'', '"',$data[21]);
			$datakolom22 = str_replace('\'', '"',$data[22]);
			$datakolom23 = str_replace('\'', '"',$data[23]);
			$datakolom24 = str_replace('\'', '"',$data[24]);
			$datakolom25 = str_replace('\'', '"',$data[25]);
			$datakolom26 = str_replace('\'', '"',$data[26]);
			$datakolom27 = str_replace('\'', '"',$data[27]);
			$datakolom28 = str_replace('\'', '"',$data[28]);
			$datakolom29 = str_replace('\'', '"',$data[29]);
			$datakolom30 = str_replace('\'', '"',$data[30]);
			$datakolom31 = str_replace('\'', '"',$data[31]);
			$datakolom32 = str_replace('\'', '"',$data[32]);
			$datakolom33 = str_replace('\'', '"',$data[33]);
			$datakolom34 = str_replace('\'', '"',$data[34]);
			$datakolom35 = str_replace('\'', '"',$data[35]);
			$datakolom36 = str_replace('\'', '"',$data[36]);
			$datakolom37 = str_replace('\'', '"',$data[37]);
			$datakolom38 = str_replace('\'', '"',$data[38]);
			$datakolom39 = str_replace('\'', '"',$data[39]);
			$datakolom40 = str_replace('\'', '"',$data[40]);
			$datakolom41 = str_replace('\'', '"',$data[41]);
			$datakolom42 = str_replace('\'', '"',$data[42]);
			$datakolom43 = str_replace('\'', '"',$data[43]);
			$datakolom44 = str_replace('\'', '"',$data[44]);
			$datakolom45 = str_replace('\'', '"',$data[45]);
			$datakolom46 = str_replace('\'', '"',$data[46]);
			$datakolom47 = str_replace('\'', '"',$data[47]);
			$datakolom48 = str_replace('\'', '"',$data[48]);
			$datakolom49 = str_replace('\'', '"',$data[49]);
			$datakolom50 = str_replace('\'', '"',$data[50]);
			
		$import="INSERT INTO $tablename
            (Cabang,Prinsipal,Supplier,Produk,No_PR,Tgl_PR,Time_PR,Status_PR,Qty_PR,Satuan,Keterangan,Penjelasan,Qty_REC,Avg,Indeks,Stok,GIT,HPC,Value_PR,User_PR,No_PO,Tgl_PO,Time_PO,Qty_PO,Status_PO,HPP,Value_PO,User_PO,No_BPB,Tgl_BPB,Time_BPB,No_SJ,No_BEX,Batch_No,Exp_Date,Qty_BPB,HPC1,Value_BPB,User_BPB,Status_BPB,No_Invoice,Tgl_Invoice,Time_Invoice,HPP1,Value_Invoice,Status_Invoice,User_Invoice,Counter_PR,Counter_PO,Counter_BPB,Counter_Invoice)
				VALUES ('$datakolom0','$datakolom1','$datakolom2','$datakolom3','$datakolom4','$datakolom5','$datakolom6','$datakolom7','$datakolom8','$datakolom9','$datakolom10','$datakolom11','$datakolom12','$datakolom13','$datakolom14','$datakolom15','$datakolom16','$datakolom17','$datakolom18','$datakolom19','$datakolom20','$datakolom21','$datakolom22','$datakolom23','$datakolom24','$datakolom25','$datakolom26','$datakolom27','$datakolom28','$datakolom29','$datakolom30','$datakolom31','$datakolom32','$datakolom33','$datakolom34','$datakolom35','$datakolom36','$datakolom37','$datakolom38','$datakolom39','$datakolom40','$datakolom41','$datakolom42','$datakolom43','$datakolom44','$datakolom45','$datakolom46','$datakolom47','$datakolom48','$datakolom49','$datakolom50')";
		echo $import;
		mysql_query($import) or die(mysql_error());
		
	}

	fclose($handle);

	print "Import done";

	$sqldeleteheader = "Delete from $tablename where Cabang = 'Cabang'";
	mysql_query($sqldeleteheader) or die(mysql_error());

	echo "<p>Proses import selesai</p>";
	header('location: ../../media.php?module=uppurch');

}
else {
	echo "<p>Proses import Gagal</p>";
	header('location: ../../media.php?module=uppurch');
}
 
?>